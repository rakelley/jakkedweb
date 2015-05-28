/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Generic internal utility module
 */
(function() {
"use strict";

define(function() {
    var module = {};

    module.objMerge = function(obj1, obj2) {
        /*
         * Recursively merge properties of two objects
         * http://stackoverflow.com/a/383245
         */
        for (var p in obj2) {
            try {
                // Property in destination object set; update its value.
                if (obj2[p].constructor == Object) {
                    obj1[p] = module.objMerge(obj1[p], obj2[p]);
                } else {
                    obj1[p] = obj2[p];
                }
            } catch(e) {
                // Property in destination object not set; create it and set its value.
                obj1[p] = obj2[p];
            }
        }
    };

    return module;
});

}());
