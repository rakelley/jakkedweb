/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Handles disabling checkboxes in a list of checkboxes with overlapping
 * responsiblities
 */
(function() {
"use strict";

define(function() {
    var module = {};

    module.init = function(attribute) {
        module.attribute = attribute || 'data-disablesRelated';

        var togglers = document.querySelectorAll('[' + module.attribute + ']');

        [].forEach.call(togglers, module.bindToggler);
    };

    module.bindToggler = function(toggler) {
        /**
         * The data attribute does not change, so we can get the element
         * list once and reuse it for all followup toggles.
         */
        var ids = toggler.getAttribute(module.attribute).split(','),
            targets = ids.map(function (id) {
                return document.getElementById(id);
            });

        module.toggleTargets(toggler, targets);

        toggler.addEventListener('change', function() {
            module.toggleTargets(toggler, targets);
        });
    };

    module.toggleTargets = function(toggler, targets) {
        var state = toggler.checked;

        targets.forEach(function(child) {
            module.toggleState(child, state);
        });
    };

    module.toggleState = function(el, state) {
        if (state && el.checked) {
            el.checked = false;
        }
        el.disabled = state;
    };

    return module;
});

}());
