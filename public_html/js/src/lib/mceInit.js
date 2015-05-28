/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Initializes tinyMCE text editor
 */
(function() {
"use strict";

define([
    'lib/utility',
    'jquery',
    'tinyMCE'
], function(utility, $, tinyMCE) {
    var module = {};

    module.options = {
        selector: '[data-tinymce]',
        content_css: '/stylesheets/layout.css',
        plugins: "autolink,code,contextmenu,image,link,paste,preview,"+
                 "searchreplace,spellchecker,table,visualchars",
        toolbar1: "undo,redo,|,formatselect,|,bold,italic,|,bullist,numlist,|,"+
                  "blockquote,subscript,superscript",
        toolbar2: "cut,copy,paste,searchreplace,|,link,unlink,image,|,"+
                  "spellchecker,visualchars,|,code,preview,help",
        width: "98%",
        height: "400",

        oninit: function(ed) {
            setTimeout(function() {
                tinyMCE.execCommand('mceSpellCheck', true);
            }, 1);
        }
    };

    module.init = function(options) {
        var merge = utility.objMerge;

        if (options) {
            merge(module.options, options);
        }

        tinyMCE.init(module.options);

        // necessary with submission via jquery form plugin
        $('form').bind('form-pre-serialize', function(e) {
            tinyMCE.triggerSave();
        });
    };


    return module;
});

}());
