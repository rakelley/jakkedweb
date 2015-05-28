/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Registers handlers and utility functions for all POST based forms
 */
(function() {
"use strict";

define([
    //form validation and result handler
    'lib/extFormHandler',
    //used for ajax responsiveness
    'lib/formUtility',
], function(formHandler, formUtility) {
    return function() {
        var forms = document.querySelectorAll('form[data-valmethods]');

        [].forEach.call(forms, function(form) {
            formHandler.init(form);
        });

        formUtility.bindResponsiveness();
    };
});

}());
