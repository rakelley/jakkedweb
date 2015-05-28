/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Page-specific js for records/plform.
 * Sets up form for ajax-based usage
 */
(function() {
"use strict";

define(['lib/getForm'], function(getForm) {
    return function() {
        // rewrite action from non-ajax default to ajax target
        var form = document.querySelector('form[data-getform]');
        form.action = 'records/plquery';

        getForm.init();
    };
});

}());
