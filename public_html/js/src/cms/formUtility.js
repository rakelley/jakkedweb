/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Form utility methods for CMS
 */
(function() {
"use strict";

define([
    'jquery',
    'password_strength'
], function($) {
    var module = {};

    module.registerInvisibleCheckboxForms = function() {
        var boxes = document.querySelectorAll('.form-invisible input[type=checkbox]');
        if (!boxes) return;

        [].forEach.call(boxes, function(box) {
            box.addEventListener('click', function() {
                $(box.form).submit();
            });
        });
    };

    module.registerPasswordStrength = function(input, target) {
        input = input || '.valNewPassword';
        target = target || '[data-passwordtime]';
        $(input).pwdstr(target);
    };

    module.getLabelForInput = function(input) {
        var labels = input.form.getElementsByTagName('label'),
            name = input.name;

        for (var i = 0, len = labels.length; i < len; i++) {
            if (labels[i].htmlFor == name) return labels[i];
        }

        return false;
    };

    module.hideRelatedOnCheckbox = function(flagSel, targetSel, state) {
        /**
         * Hides and shows form element target depending on state of checkbox flag
         * 
         * @param string  flagSel   selector for checkbox whose state is watched
         * @param string  targetSel selector for element to be hidden
         * @param boolean state     flag state in which target is hidden
         */
        var flag = document.querySelector(flagSel),
            target = document.querySelector(targetSel);
        target.label = module.getLabelForInput(target);

        var toggle = function() {
            if (flag.checked === state) {
                target.classList.add('hidden');
                if (target.label) target.label.classList.add('hidden');
            } else {
                target.classList.remove('hidden');
                if (target.label) target.label.classList.remove('hidden');
            }
        };

        toggle();
        flag.addEventListener('change', toggle);
    };

    return module;
});

}());
