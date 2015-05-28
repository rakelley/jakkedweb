/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Config for requireJS
 */
requirejs.config({
    baseUrl: 'js/vendor',
    paths: {
        'cms': '../src/cms',
        'main': '../src/main',
        'lib': '../src/lib',
        'ace': 'ace-1.1.3/ace',
        'jquery': [
            '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min',
            'jquery-2.1.1/jquery.min'
        ],
        'jquery_form': [
            '//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.50/jquery.form.min',
            'jquery-form-3.50/jquery.form.min'
        ],
        'jquery_validate': [
            '//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min',
            'jquery-validate-1.12.0/jquery.validate.min'
        ],
        'jquery_validate_addtlMethods': [
            '//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min',
            'jquery-validate-1.12.0/additional-methods.min'
        ],
        'lightbox': 'lightbox2-2.7.1/lightbox.min',
        'modals': 'modals-master/modals',
        'password_strength': 'password-strength/jquery.pwdstr-1.0.source',
        'tinyMCE': 'tinymce-4.1.0/tinymce.min'
    },
    shim: {
        ace: {
            exports: 'ace',
            init: function() { return this.ace; }
        },
        jquery: { exports: 'jquery' },
        jquery_validate: { deps: ['jquery'], exports: 'jquery' },
        jquery_validate_addtlMethods: {
            deps: ['jquery_validate'],
            exports: 'jquery'
        },
        lightbox: { deps: ['jquery'], exports: 'lightbox' },
        password_strength: { deps: ['jquery'], exports: 'jquery' },
        tinyMCE: {
            exports: 'tinyMCE',
            init: function() {
                this.tinyMCE.DOM.events.domLoaded = true;
                return this.tinyMCE;
            }
        }
    }
});
