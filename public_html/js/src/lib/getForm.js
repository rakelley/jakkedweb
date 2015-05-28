/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Provides ajax-based functionality for GET forms with state
 */
(function() {
"use strict";

define([
    'lib/formUtility',
    'jquery',
    'jquery_form'
], function(formUtility, $) {
    var module = {};

    module.bindGetter = function(form) {
        var target = module.getResponseTarget(form),
            options = {
                dataType: "json",
                success: function(reponse) {
                    target.html(reponse);
                }
            };

        form.addEventListener('submit', function(e) {
            $(form).ajaxSubmit(options);
            e.preventDefault();
        });
    };

    module.bindStatePusher = function(form) {
        form.addEventListener('submit', function() {
            var state = window.location.pathname + '?' + $(form).serialize();
            window.history.pushState({'url':state}, document.title, state);
        });

        window.onpopstate = function(e) {
            if (e && e.state) {
                window.location.reload();
            } else {
                module.getResponseTarget(form).html('');
            }
        }
    };

    module.getResponseTarget = function(form) {
        return $(form).prevAll('[data-ajaxtarget]:first');
    };

    module.init = function(selector) {
        selector = selector || 'form[data-getform]';
        var forms = document.querySelectorAll(selector);
        if (!forms) return;

        [].forEach.call(forms, function(form) {
            module.bindGetter(form);
            if (form.hasAttribute('data-stateful')) {
                module.bindStatePusher(form);
            }
        });

        formUtility.bindResponsiveness();
    };

    return module;
});

}());
