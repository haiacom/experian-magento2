
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
    'MageConnect_Autocomplete/js/model/elements'
], function ($, elements) {
    'use strict';
    return {
        enabled: true,
        init: function (options) {
            Object.assign(this, options);
        },
        error: function(msg) {
            console.log(msg);
        },
        getCountryCode: function () {
            return elements.findElement('country_id') ? elements.findElement('country_id').val() : this.defaultCountryCode;
        },
        getCountryIso: function () {
            return this.getMappings('iso3') ?? this.defaultCountryIso;
        },
        getMappings: function(type) {
            var countryCode = this.getCountryCode();
            if (countryCode) {
                var mappings = this.datasetMappings[countryCode];
                if (mappings && mappings[type]) {
                    return mappings[type];
                }
            }
            return null;
        },
        getDataset: function () {
            return this.getMappings('dataset') ?? null;
        },
        getInstance: function () {
            return this;
        },
        addPatterns: function (name) {
            var self = this, newElements = self[name];
            $.each(newElements, function (i, newElm) {
                if (self.elementPatterns.indexOf(newElm) == -1) {
                    self.elementPatterns.push(newElm);
                }
            });
        }
    };
});
