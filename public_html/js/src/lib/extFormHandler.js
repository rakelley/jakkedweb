/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

(function() {
"use strict";
define([
    //includes jQuery, jQuery Form, and jQuery Validate
    'lib/validation_addtlRules',
    // used for object merge function
    'lib/utility',
], function($, utility) {
    var module = {};

    /**
     * Container of defined methods for handling form submission success/failure
     * Each function can optionally accept two params:
     *     {object} form     Form element node
     *     {object} response Server response object
     *         {boolean} success Whether the action was successful or not
     *         {string}  error   If success is false, error message will be
     *                           provided
     *         {string}  message If success is true, message may be provided
     * @type {Object}
     */
    module.handlerMethods = {
        success: {
            'reload': function() {
                window.location.reload();
            },
            'nothing': function() {
                return;
            },
            'redirect': function(form) {
                if (form.hasAttribute('data-redirect')) {
                    window.location = form.getAttribute('data-redirect');
                }
            },
            'replace': function(form, response) {
                form.innerHTML = response.message;
            },
            'show': function(form) {
                $(".form-status-error").hide();
                $(".form-status-success").hide();
                $(".form-status-success", form).show();
                $('fieldset', form).hide();
            }
        },
        error: {
            'hide': module.genericError,
            'show': function(form, response) {
                $(".form-status-error").hide();
                $(".form-status-success").hide();
                var errorElement = $(".form-status-error", form);
                $(errorElement).html('<li>'+response.error+'</li>');
                $(errorElement).show();
            }
        }
    };

    /**
     * Defines and binds a submission handler to a form
     * 
     * @param  {object} form      Form element node
     * @param  {object} overrides Optional options object to use in place of
     *                            defaults, {@see module.getOptions}
     */
    module.init = function(form, overrides) {
        var valMethods = form.getAttribute('data-valMethods').split('-'),
            rules = {
                'success': valMethods[0],
                'error': valMethods[1],
                'confirm': form.hasAttribute('data-confirm')
            };

        var options = module.getOptions(form, rules, overrides);

        $(form).validate({
            submitHandler: function(form) {
                $(form).ajaxSubmit(options);
                return false;
            }
        });
    };

    /**
     * Creates an Options object for passing to .ajaxSubmit with success/error
     * handlers pulled from module.handlerMethods
     * {@link http://malsup.com/jquery/form/#options-object}
     * 
     * @param  {object} form      Form element node
     * @param  {object} rules     Object containing properties to define methods
     *                            used to handle form
     *     {string}  success name of success handler method to use
     *     {string}  error   name of error handler method to use
     *     {boolean} confirm whether to confirm submission with user
     * @param  {object} overrides Optional provided options object to overrule
     *                            default/generated one
     * @return {object}           Options object
     */
    module.getOptions = function(form, rules, overrides) {
        var merge = utility.objMerge,
            onsuccess = module.handlerMethods.success[rules['success']],
            onerror = module.handlerMethods.error[rules['error']];

        var options = {
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    onsuccess(form, response);
                } else {
                    onerror(form, response);
                }
            },
            error: module.genericError
        };

        if (rules.confirm) {
            options.beforeSubmit = module.confirmSubmission;
        }
        if (overrides) {
            merge(options, overrides);
        }

        return options;
    }

    /**
     * Method for a generic error response to unanticipated condition
     */
    module.genericError = function() {
        alert('An Unexpected Error Occurred, Please Contact the Admin If This '+
              'Persists');
    };

    /**
     * Confirm with user before submitting form
     * Fullfills jQuery form plugin beforeSubmit interface
     * {@link http://malsup.com/jquery/form/#options-object}
     * 
     * @param  {array}   data    Form values
     * @param  {object}  form    jQuery object for form
     * @param  {object}  options ajaxSubmit options object
     * @return {boolean}         User input, false will cancel submission
     */
    module.confirmSubmission = function(data, form, options) {
        var confirmation = $(form).attr('data-confirm') ||
                           "Are You Certain You Wish to Do This?";

        return confirm(confirmation);
    };

    return module;
});

}());
