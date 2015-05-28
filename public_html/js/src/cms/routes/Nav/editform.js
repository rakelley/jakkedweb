/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Page-specific js for CMS nav/editform
 */
(function() {
"use strict";

define([
    'cms/formUtility'
], function(utility) {
    return function() {
        utility.hideRelatedOnCheckbox('#isparent', '#parent', true);
    }
});

}());
