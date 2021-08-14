
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
    'MageConnect_Autocomplete/js/model/elements'
], function ($, service, elements) {
    'use strict';

    return {
        getService: function() {
            return service.getInstance();
        },
        post: function () {
            var currVal = elements.getCurrVal();
            var data = {
                "country_iso": this.getService().getCountryIso(),
                    "components": {
                    "unspecified": [
                        currVal
                    ]
                }
            };
            if (this.getService().getDataset()) {
                data.dataset = [this.getService().getDataset()];
            }
            var data = JSON.stringify(data);
            return $.ajax({
                url: this.getService().endpoint,
                type: 'POST',
                data: data,
                global: true,
                headers: this.getHeader(),
                contentType: 'application/json'
            });
        },
        getHeader: function () {
            return {"auth-token": this.getService().token, "timeout-seconds": this.getService().timeout};
        },
        get: function (url) {
            var endpoint = url || this.getService().endpointFormat;
            return $.ajax({
                url: endpoint,
                type: 'GET',
                global: true,
                headers: this.getHeader(),
                contentType: 'application/json'
            });
        },
    };
});
