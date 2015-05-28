/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

(function() {
"use strict";

/**
 * Get page-specific script file from data-page attribute if it exists.
 * Page-specific file should return a function, object with init method, or
 * empty object if no action is required beyond loading dependencies.
 */
define(function() {
    return function() {
        var script = document.querySelector('script[data-main][data-page]');
        if (!script) return;

        var name = script.getAttribute('data-page');
        require([name], function(pageMod) {
            var func = (typeof pageMod === 'function') ? pageMod : pageMod.init;
            if (func) func();
        });
    };
});

}());
