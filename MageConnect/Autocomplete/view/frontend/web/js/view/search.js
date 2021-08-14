
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
    'MageConnect_Autocomplete/js/action/request',
    'MageConnect_Autocomplete/js/view/picklist',
    'MageConnect_Autocomplete/js/model/events',
    'MageConnect_Autocomplete/js/model/elements',
    'experianService'
], function ($, request, picklist, events, elements, service) {
    'use strict';
    return {
        init: function() {
            this.service = service.getInstance();
            var self = this;
            if (this.service.elementPatterns) {
                $.each(this.service.elementPatterns, function(key, el) {
                    self.trigger(el, true);
                });
            }
        },
        getService: function () {
            return this.service;
        },
        trigger: function(el, first = false) {
            var el = el ? $(el) : null;
            if (el) {
                el.on("keyup", this.search.bind(this));
                el.on("keydown", this.checkTab.bind(this));
                el.attr("autocomplete", "off");
                if (first) {
                    if (service.applyFocus) {
                        el.focus();
                    }
                    el.attr("placeholder", service.placeholderText);
                }
                elements.add(el);
            }
            else {
                this.service.error('In element does not exist');
            }
        },
        unbind: function () {
            elements.get().forEach(function (el) {
                el.off("keyup", this.search);
                el.off("keydown", this.checkTab);
                el.removeAttr("autocomplete");
            });
        },
        checkTab: function (event) {
            var e = event || window.event;
            e = e.which || e.keyCode;
            if (e === 9 /*Tab*/) {
                picklist.keyup(e);
                return;
            }
        },
        search: function (event) {
            // Handle keyboard navigation
            elements.setCurrent($(event.target));
            var e = event || window.event;
            e = e.which || e.keyCode;
            if (e === 38/*Up*/ || e === 40/*Down*/ || e === 13/*Enter*/) {
                picklist.keyup(e);
                return;
            }
            if (elements.getCurrVal() == elements.getLastVal() || elements.getCurrVal().length < this.service.minSearchLength) {
                return;
            }
            elements.setLastVal(elements.getCurrVal());

            if (this.canSearch()) {
                // Fire an event before a search takes place
                events.trigger("pre-search", this.service.currentSearchTerm);
                var resultHistory = elements.getStoredResult();
                if (resultHistory && resultHistory.result) {
                    picklist.show(resultHistory);
                    this.currentSearch = false;
                }
                else {
                    // Initiate new Search request
                    request.post().done(function (response) {
                        picklist.show(response);
                        elements.storeResult(response);
                    }).error(function (response) {
                        picklist.hide();
                    }).always(function (response) {
                        elements.setLastVal(elements.getCurrVal());
                    });
                }
            } else {
                picklist.hide();
            }
        },
        canSearch: function () {
            return true;
        }
    }
});
