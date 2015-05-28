/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Page-specific js for flat/index (site home page).
 *
 * Performs below-the-fold ajax calls and fills in results
 */
(function() {
"use strict";

define([
    'lib/simpleAjax',
    'lib/tabs',
    'lib/columnEqualizer'
], function(simpleAjax, tabs, equalizer) {
    var fillResponse = function(response, target) {
        if (typeof response === 'string') {
            document.querySelector(target).innerHTML = response;
        }
    };

    /** @type {Object} Recent news widget */
    var newsArgs = {
        url: '/events/news',
        success: function(response) {
            fillResponse(response, '.news');
        }
    },
    /** @type {Object} Google calendar widget */
    gcalArgs = {
        url: '/events/calendar',
        success: function(response) {
            fillResponse(response, '.gcal-wrapper');
        }
    },
    /** @type {Object} YouTube widget */
    ytArgs = {
        url: '/events/videos',
        success: function(response) {
            fillResponse(response, '.youtube-wrapper');
            tabs.init();
            equalizer.init();
        }
    };

    return function() {
        simpleAjax(newsArgs);
        simpleAjax(gcalArgs);
        simpleAjax(ytArgs);
    };
});

}());
