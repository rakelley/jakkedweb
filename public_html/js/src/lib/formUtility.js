/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Module for utility methods for forms not related to submission
 */
(function() {
"use strict";

define([
    'jquery'
], function($) {
    var module = {};

    /**
     * Binds ajax_overlay and anti-repeat-submission to ajax events
     * Depends on jQuery
     */
    module.bindResponsiveness = function() {
        var submitters = document.querySelectorAll('input[type=submit'),
            overlay = document.querySelector('.ajax_overlay');

        $(document).ajaxStart(function() {
            [].forEach.call(submitters, function(input) {
                input.disabled = true;
            });
            overlay.style.display = 'block';
        });
        $(document).ajaxStop(function() {
            [].forEach.call(submitters, function(input) {
                input.disabled = false;
            });
            overlay.style.display = 'none';
        });
    };

    return module;

});

}());
