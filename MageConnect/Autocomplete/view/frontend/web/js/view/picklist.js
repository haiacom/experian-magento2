
/*
 *
 *  *  Developed by MageConnect
 *  *  Do not edit or add to this file if you wish to upgrade This to newer
 *  *  versions in the future.
 *  *  @author      minhhaislvl@gmail.com
 *  *
 *
 */

define([
    'jquery',
    'experianService',
    'MageConnect_Autocomplete/js/action/request',
    'MageConnect_Autocomplete/js/model/events',
    'MageConnect_Autocomplete/js/view/result',
    'MageConnect_Autocomplete/js/model/elements',
], function ($, service, request, events, result, elements) {
    'use strict';
    var service = service.getInstance();
    return {
        items: {},
        // Set initial size
        size: 0,
        // Set initial max size
        maxSize: 25,
        // Tab count used for keyboard navigation
        tabCount: -1,
        // Render a picklist of search results
        show: function (data) {
            if (!data || !data.result) {
                service.error(data);
                return;
            }
            // Store the picklist items
            this.items = data.result.suggestions;

            // Reset any previously selected current item
            this.currentItem = null;

            // Update picklist size
            this.size = this.items.length;

            // Get/Create picklist container element
            this.list = this.list || this.createList();

            // Ensure previous results are cleared
            this.list.html("");

            // Reset the picklist tab count (used for keyboard navigation)
            this.resetTabCount();

            if (this.size > 0) {
                // Fire an event before picklist is created
                events.trigger("pre-picklist-create", this.items);
                var self = this;
                // Iterate over and show results
                this.items.forEach(function (item) {
                    // Create a new item/row in the picklist
                    var listItem = self.createListItem(item);
                    self.list.append(listItem);
                    // Listen for selection on this item
                    self.listen(listItem);
                });
                // Fire an event after picklist is created
                events.trigger("post-picklist-create");
            }
        },
        hide: function () {
            // Clear the current picklist item
            this.currentItem = null;
            // Remove the main picklist container
            if (this.list) {
                this.container.remove();
                this.list = undefined;
            }
        },
        // Create the picklist list (and container) and inject after the input
        createList: function () {
            var self = this;
            var container = $("<div>");
            container.addClass("address-picklist-container");
            // Insert the picklist container after the input
            elements.getCurrent().after(container);
            self.container = container;

            var list = $("<div>");
            list.addClass("address-picklist");
            // Append the picklist to the container
            container.append(list);

            // list.on("keydown", self.enter);
            return list;
        },
        // Create a new picklist item/row
        createListItem: function (item) {
            var row = $("<div>"), self = this;
            row.html(self.addMatchingEmphasis(item));
            // Store the Format URL
            row.attr({"format": item.format});
            row.attr({"glob_key": item.global_address_key});
            return row;
        },
        resetTabCount: function () {
            this.tabCount = -1;
        },
        // Keyboard navigation
        keyup: function (e) {
            if (!this.list) {
                return;
            }

            if (e === 13/*Enter*/ || e === 9 /*Tab*/) {
                this.checkEnter();
                return;
            }

            // Get a list of all the addresses in the picklist
            var addresses = this.list.find("div"),
                firstAddress, lastAddress;

            // If the picklist is empty, just return
            if (addresses.length === 0) {
                return;
            }

            // Set the tabCount based on previous and direction
            if (e === 38/*Up*/) {
                this.tabCount--;
            } else {
                this.tabCount++;
            }

            // Set top and bottom positions and enable wrap-around
            if (this.tabCount < 0) {
                this.tabCount = addresses.length - 1;
                lastAddress = true;
            }
            if (this.tabCount > addresses.length - 1) {
                this.tabCount = 0;
                firstAddress = true;
            }

            // Highlight the selected address
            var currentlyHighlighted = addresses[this.tabCount];
            // Remove any previously highlighted ones
            var previouslyHighlighted = this.list.find(".selected");
            if (previouslyHighlighted) {
                previouslyHighlighted.removeClass("selected");
            }
            currentlyHighlighted.addClass("selected");
            // Set the currentItem on the picklist to the currently highlighted address
            this.currentItem = currentlyHighlighted;

            // Scroll address into view, if required
            var addressListCoords = {
                top: this.list.offsetTop,
                bottom: this.list.offsetTop + this.list.offsetHeight,
                scrollTop: this.list.scrollTop,
                selectedTop: currentlyHighlighted.offsetTop,
                selectedBottom: currentlyHighlighted.offsetTop + currentlyHighlighted.offsetHeight,
                scrollAmount: currentlyHighlighted.offsetHeight
            };
            if (firstAddress) {
                this.list.scrollTop = 0;
            } else if (lastAddress) {
                this.list.scrollTop = 999;
            } else if (addressListCoords.selectedBottom + addressListCoords.scrollAmount > addressListCoords.bottom) {
                this.scrollTop = addressListCoords.scrollTop + addressListCoords.scrollAmount;
            } else if (addressListCoords.selectedTop - addressListCoords.scrollAmount - addressListCoords.top < addressListCoords.scrollTop) {
                this.scrollTop = addressListCoords.scrollTop - addressListCoords.scrollAmount;
            }
        },
        // Add emphasis to the picklist items highlighting the match
        addMatchingEmphasis: function (item) {
            var highlights = item.matched || [],
                label = item.text;

            for (var i = 0; i < highlights.length; i++) {
                var replacement = '<b>' + label.substring(highlights[i][0], highlights[i][1]) + '</b>';
                label = label.substring(0, highlights[i][0]) + replacement + label.substring(highlights[i][1]);
            }

            return label;
        },
        // Listen to a picklist selection
        listen: function (row) {
            row.on("click", this.pick.bind(this, row));
        },
        checkEnter: function () {
            var picklistItem;
            // If picklist contains 1 address then use this one to format
            if (service.picklist.size === 1) {
                picklistItem = service.picklist.list.querySelectorAll("div")[0];
            } // Else use the currently highlighted one when navigation using keyboard
            else if (service.picklist.currentItem) {
                picklistItem = service.picklist.currentItem;
            }
            if (picklistItem) {
                service.picklist.pick(picklistItem);
            }
        },
        // How to handle a picklist selection
        pick: function (item) {
            // Fire an event when an address is picked
            events.trigger("post-picklist-selection", item);

            // Get a final address using picklist item
            this.format(item.attr("format"), item.attr('glob_key'));
        },
        format: function (url, key) {
            var self = this;
            // Trigger an event
            events.trigger("pre-formatting-search", url);
            var history = elements.getStoredFormatResult(key);
            if (history && history.result) {
                result.render(history, service);
            }
            else {
                // Initiate a new Format request
                request.get(url)
                    .done(function (response) {
                        elements.storeFormatResult(response, key);
                        result.render(response, service);
                    }).error(function (response) {
                }).always(function (response) {
                    self.hide();
                });
            }
        }
    }
});
