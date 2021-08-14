
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
        collection: {},
        on: function (event, action) {
            // Create the property array on the collection object
            this.collection[event] = this.collection[event] || [];
            // Push a new action for this event onto the array
            this.collection[event].push(action);
        },
        trigger: function (event, data) {
            // If this event is in our collection (i.e. anyone's subscribed)
            if (this.collection[event]) {
                // Loop over all the actions for this event
                for (var i = 0; i < this.collection[event].length; i++) {
                    // Create array with default data as 1st item
                    var args = [data];

                    // Loop over additional args and add to array
                    for (var a = 2; a < arguments.length; a++) {
                        args.push(arguments[a]);
                    }

                    // Call each action for this event type, passing the args
                    try {
                        this.collection[event][i].apply(this.collection, args);
                    } catch (e) {
                        console.error(e);
                    }
                }
            }
        }
    }
});
