/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Sets up Ace code editor for use
 */
(function() {
"use strict";

define([
    'lib/utility',
    'ace'
], function(utility, ace) {
    var module = {};

    module.target;
    module.editor;
    module.options = {
        targetSelector: '[data-acetarget]',
        editorId: 'aceEditor',
        theme: 'monokai',
        mode: 'php'
    };

    module.init = function(options) {
        var merge = utility.objMerge;

        if (options) {
            merge(module.options, options);
        }

        module.target = document.querySelector(module.options.targetSelector);
        module.createEditorElement();
        module.initializeEditor();
        module.bindListener();
    };

    /**
     * Creates dummy div to hold editor window
     */
    module.createEditorElement = function() {
        var el = document.createElement('div');
        el.id = module.options.editorId;
        // tabindex needed in order to provide keyup event
        el.setAttribute('tabindex', '1');
        module.target.parentNode.insertBefore(el, module.target.nextSibling);
    };

    /**
     * Initializes editor window
     */
    module.initializeEditor = function() {
        module.editor = ace.edit(module.options.editorId);
        module.editor.setTheme("ace/theme/"+module.options.theme);
        module.editor.getSession().setMode("ace/mode/"+module.options.mode);
        module.editor.getSession().setValue(module.target.value);
    };

    /**
     * Creates listener to transfer editor content to form textarea content for
     * submission
     */
    module.bindListener = function() {
        var editorDiv = document.querySelector("#"+module.options.editorId);
        editorDiv.addEventListener('keyup', function() {
            module.target.innerHTML = module.editor.getSession().getValue();
        });
    }

    return module;
});

}());
