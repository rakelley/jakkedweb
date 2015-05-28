/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Equalizes the heights of a set of columns immediately and on window resize
 */
(function() {
"use strict";

define(function() {
    var module = {};

    module.equalized = false;
    module.breakpoint = 767;//minimum window width to equalize at

    /**
     * Module initialization
     * 
     * @param  {[String]} selector Element selector that defines columns to
     *                             equalize
     * @param  {[Int]} breakpoint  Minimum window width in pixels to equalize at
     */
    module.init = function(selector, breakpoint) {
        if (typeof breakpoint !== 'undefined') {
            module.breakpoint = breakpoint;
        }
        selector = selector || '.equalize';
        module.columns = document.querySelectorAll(selector);

        if (window.innerWidth > module.breakpoint) {
            module.main();
        }

        var observer = new MutationObserver(module.main);
        [].forEach.call(module.columns, function(column) {
            observer.observe(column, {subtree: true, childList: true});
        });

        window.addEventListener('resize', module.main);
    };

    /**
     * Callback for state changes, reverts previous equalizations and performs
     * new if appropriate
     */
    module.main = function() {
        if (module.equalized) {
            module.revert(module.columns);
            module.equalized = false;
        }
        if (window.innerWidth > module.breakpoint) {
            module.equalize(module.columns);
            module.equalized = true;                    
        }
    }

    /**
     * Function for equalizing heights
     * 
     * @param  {[Object]} nodeList Node list of columns to equalize
     */
    module.equalize = function(nodeList) {
        var heights = [].map.call(nodeList, function (node) {
            return node.offsetHeight;
        });
        var max = Math.max.apply(Math, heights);

        [].forEach.call(nodeList, function (node) {
            node.style.height = max + 'px';
        });
    };

    /**
     * Reverts previous equalization
     * @param  {[Object]} nodeList Node list of columns to reset
     */
    module.revert = function(nodeList) {
        [].forEach.call(nodeList, function (node) {
            node.style.height = '';
        });
    };

    return module;
});

}());
