
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
        'uiComponent',
        'experianService',
        'experianSearch'
    ],
    function (
        $,
        Component,
        service,
        search
    ) {
        return Component.extend({
            initialize: function () {
                this._super();
                service.init(this.settings);
                return this;
            },
            searchInit: function () {
                if (this.elementPatterns) {
                    service.addPatterns(this.elementPatterns);
                }
                setTimeout(function() {
                    search.init();
                }, 1000);

            }
        });
    }
);
