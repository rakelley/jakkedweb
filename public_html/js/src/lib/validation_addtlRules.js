/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Provides site-specific validation rules for jQuery Validate
 */
(function() {
"use strict";

define([
    'jquery',
    'jquery_form',
    'jquery_validate',
    'jquery_validate_addtlMethods'
], function($) {
    var customRuleSet = {
        valName: {
            minlength: 2
        },
        valPageTitle: {
            minlength: 2,
            maxlength: 75
        },
        valTextarea: {
            minlength: 20
        },
        valPassword: {
            minlength: 8
        },
        valNewPassword: {
            minlength: 8
        },
        valConfirmPassword: {
            minlength: 8,
            equalTo: '.valNewPassword'
        },
        valImage: {
            accept: "image/png,image/jpeg,image/gif"
        },
        valDateTime: {
            onkeyup: false,
            remote: {
                url: '/validate/datetime',
                type: 'post',
            }
        },
        valArticleTitle: {
            minlength: 20,
            maxlength: 200
        },
        valUsername: {
            minlength: 5,
            email: true
        },
        valNewUsername: {
            minlength: 5,
            email: true,
            onkeyup: false,
            remote: {
                url: '/validate/usernotexists',
                type: 'post',
            }
        },
        valExistUsername: {
            minlength: 5,
            email: true,
            onkeyup: false,
            remote: {
                url: '/validate/userexists',
                type: 'post',
            }
        },
        valSpamcheck: {
            onkeyup: false,
            remote: {
                url: '/validate/spamcheck',
                type: 'post',
            }
        },
        valFile: {
            accept: "image/png,image/jpeg,image/gif,application/pdf"
        },
        valDescription: {
            minlength: 20
        },
        valNewPage: {
            minlength: 2,
            onkeyup: false,
            remote: {
                url: '/validate/pagenotexists',
                type: 'post',
            }
        },
        valDate: {
            onkeyup: false,
            remote: {
                url: '/validate/date',
                type: 'post',
            }
        }
    };

    $.validator.addClassRules(customRuleSet);
    return $;
});

}());
