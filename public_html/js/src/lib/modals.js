/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Provides functionality for modal windows
 */
(function() {
"use strict";

define(['modals'], function(modals) {
    var module = {};

    /**
     * Creates a standard header for a modal element
     * 
     * @param  {object} modal Element node
     */
    module.createHeader = function(modal) {
        var header = document.createElement('header');
        header.classList.add('modal-header');

        if (modal.hasAttribute('title')) {
            var heading = document.createElement('h3');
            heading.classList.add('modal-heading');
            heading.innerHTML = modal.getAttribute('title');
            header.appendChild(heading);
        }

        var closer = document.createElement('button');
        closer.classList.add('modal-close');
        closer.setAttribute('data-modal-close', '');
        closer.innerHTML = 'X';
        header.appendChild(closer);

        modal.insertBefore(header, modal.firstChild);
    };

    /**
     * Takes existing modal contents and wraps them in a
     * <section class="modal-body"></section>
     * 
     * @param  {object} modal Element node
     */
    module.wrapBody = function(modal) {
        var wrapper = document.createElement('section');
        wrapper.classList.add('modal-body');

        // have to use while instead of forEach as index resets with each append
        while (modal.children.length > 0) {
            wrapper.appendChild(modal.children[0]);
        }
        modal.appendChild(wrapper);
    };

    /**
     * Prepares all modals for display and then initializes 3rd party modal lib
     * 
     * @param  {string} selector Selector for identifying modal elements
     */
    module.init = function(selector) {
        selector = selector || '[data-modal-window]';
        var modalList = document.querySelectorAll(selector);
        if (!modalList) return;

        [].forEach.call(modalList, function(modal) {
            module.wrapBody(modal);
            module.createHeader(modal);
        });

        modals.init();
    };

    return module;
});

}());
