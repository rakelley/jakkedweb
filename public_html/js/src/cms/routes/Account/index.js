/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Page-specific js for CMS account/index
 */
(function() {
"use strict";

define([
    'lib/mceInit',
    'cms/formUtility',
], function(mce, formUtility) {

    return function() {
        mce.init();
        formUtility.registerPasswordStrength();
    };
});

}());
