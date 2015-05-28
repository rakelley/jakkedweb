/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Provides functionality for tab-based elements
 */
(function() {
"use strict";

define([
    'lib/genericActivator'
], function(activator) {
    var module = {};

    module.init = function(selector) {
        selector = selector || 'ul.tabs';
        var tabGroups = document.querySelectorAll(selector);

        [].forEach.call(tabGroups, module.bindGroup);
    };
 
    module.bindGroup = function(listEl) {
        var tabs = [].map.call(listEl.children, function (listItemEl) {
                return listItemEl.children[0];
            });

        tabs.forEach(function (tab) {
            tab.tabGroup = tabs;
            tab.addEventListener('click', module.openTab);
        });
    }

    module.openTab = function(e) {
        e.preventDefault();

        var tab = this,
            target = document.querySelector(tab.getAttribute('href')),
            allContent = target.parentNode.children;

        tab.tabGroup.forEach(activator.makeInactive);
        activator.makeActive(tab);

        [].forEach.call(allContent, activator.makeInactive);
        activator.makeActive(target);
    };

    return module;
});

}());
