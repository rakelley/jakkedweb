/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Page-specific js for CMS files/addform
 */
(function() {
"use strict";

define(function() {
    return function() {
        /**
         * Shows/hides gallery select input when filetype input is set to/from
         * gallery
         */
        var type = document.getElementById('filetype');
        type.addEventListener('change', function() {
            if (type.value === 'galleryimage') {
                document.getElementById('gallery').classList.remove('hidden');
            } else {
                document.getElementById('gallery').classList.add('hidden');
            }
        });
    };
});

}());
