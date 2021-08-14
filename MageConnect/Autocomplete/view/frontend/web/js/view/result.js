
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
    'MageConnect_Autocomplete/js/model/events',
    'MageConnect_Autocomplete/js/model/elements'
], function ($, events, elements) {
    'use strict';

    return {
        render: function (data, service) {
            var service = service.getInstance();
            if (!data || !data.result) {
                return;
            }
            var result = data.result;
            if (result.address && Object.keys(result.address).length > 0) {
                $.each(result.address, function(key, value) {
                    if (service.elements[key] && elements.findElement(service.elements[key])) {
                        if (key != 'country') {
                            if (key == 'region') {
                                var currCountry = service.getCountryCode(), regionMappings = service.existingRegions;
                                if (currCountry && regionMappings[currCountry]) {
                                    value = regionMappings[currCountry][value] ?? value;
                                }
                            }
                            var element = elements.findElement(service.elements[key], key);
                            element.val(value);
                            element.trigger('change');
                        }
                    }
                });
            }
            events.trigger("post-formatting-search", data);
        }
    }
});
