/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Generic activation module for use in other widgets
 */
(function() {
"use strict";

define(function() {
    var module = {};

    module.makeActive = function(element) {
        element.classList.add('active');
    };

    module.makeInactive = function(element) {
        element.classList.remove('active');
    };

    module.isActive = function(element) {
        return element.classList.contains('active');
    }

    return module;
});

}());
