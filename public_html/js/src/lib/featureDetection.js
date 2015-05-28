/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Browser feature detection
 */
(function() {
"use strict";

define(function() {
    return function() {
        if ('querySelector' in document &&
            'addEventListener' in document &&
            Array.prototype.forEach &&
            Array.prototype.map
        ) {
            document.documentElement.classList.remove('no-js');
            return true;
        } else {
            return false;
        }
    };
});

}());
