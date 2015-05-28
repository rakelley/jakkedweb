/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Common js for all public-site pages
 */
(function() {
"use strict";

define([
    'lib/featureDetection',
    'lib/dropdownNav',
    'lib/loadPage'
], function(featureDetection, nav, loadPage) {
    var module = {};

    module.init = function() {
        if (!featureDetection()) return;

        nav.init();
        loadPage();
    };

    return module;
});

}());
