/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * main.js for CMS site
 */
require(['../config'], function() {
    require([
        'cms/common'
    ],
        function(common) {
            common.init();
        }
    );
});
