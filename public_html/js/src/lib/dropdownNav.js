/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Provides functionality for dropdown-based navigation
 */
(function() {
"use strict";

define([
    'lib/genericActivator'
], function(activator) {
    var module = {};

    module.init = function(selector) {
        selector = selector || '.js-listToggler';
        var togglers = document.querySelectorAll(selector);

        [].forEach.call(togglers, function(toggler) {
            toggler.addEventListener('click', module.toggleList);
        });
    }

    module.toggleList = function(e) {
        e.preventDefault();

        var toggler = this,
            list = toggler.nextElementSibling,
            text = toggler.innerHTML;

        if (activator.isActive(toggler)) {
            [toggler, list].forEach(activator.makeInactive);
            text = text.replace('▴', '▾');
        } else {
            [toggler, list].forEach(activator.makeActive);
            text = text.replace('▾', '▴');
        }

        toggler.innerHTML = text;
    };

    return module;
});

}());
