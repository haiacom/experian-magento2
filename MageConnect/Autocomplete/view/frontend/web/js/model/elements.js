
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
    'jquery'
], function ($) {
    'use strict';
    return {
        maxHistorySize: 10,
        collection: [],
        resultHistory: [],
        formatHistory: [],
        lastSearchValue: '',
        add: function (element) {
            this.collection.push(element);
        },
        get: function(id = null) {
            if (id) {
                this.collection.forEach(function(el) {
                    if (el.id == id) {
                        return el;
                    }
                });
            }
            else {
                return this.collection;
            }
            return null;
        },
        setLastVal: function(v) {
            this.lastSearchValue = v;
        },
        getLastVal: function() {
            return this.lastSearchValue;
        },
        getCurrVal: function() {
            return this.getCurrent() ? this.getCurrent().val() : '';
        },
        storeFormatResult: function(result, key) {
            this.formatHistory = this.addTo(this.formatHistory, result, key);
        },
        addTo: function (history, result, key) {
            if (history.length >= this.maxHistorySize) {
                history = history.slice(history.length - (this.maxHistorySize - 1));
            }
            history.push({
                'searchKey': key,
                'searchResult': result
            });
            return history;
        },
        storeResult: function(result) {
            this.resultHistory = this.addTo(this.resultHistory, result, this.getCurrVal());
        },
        getStoredResult: function () {
            var self = this, currVal = this.getCurrVal(), result = {};
            $.each(this.resultHistory, function(k, v) {
                if (v.searchKey == currVal) {
                    result = v.searchResult ?? result;
                }
            });
            return result;
        },
        getStoredFormatResult: function (key) {
            var result = {};
            $.each(this.resultHistory, function(k, v) {
                if (v.searchKey == key) {
                    result = v.searchResult ?? result;
                }
            });
            return result;
        },
        getCurrent: function () {
            return this.currentElm;
        },
        setCurrent: function (el) {
            this.currentElm = el;
        },
        findElement: function (name, key) {
            var element = this.getCurrent().closest('form').find('[name="'+name+'"]');
            if (!element.length) {
                switch(key) {
                    case 'address_line_1':
                        element = this.getCurrent().closest('form').find('#street_1');
                        break;
                    case 'address_line_2':
                        element = this.getCurrent().closest('form').find('#street_2');
                        break;
                    case 'address_line_3':
                        element = this.getCurrent().closest('form').find('#street_3');
                }
            }
            return element;
        }
    }
});
