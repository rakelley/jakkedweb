/**
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

/**
 * Utility implementation of basic ajax requests
 */
(function() {
"use strict";

define(function () {
    return function (options) {
        options.requestType = options.requestType || 'get';
        options.responseType = options.responseType || 'json';

        var req = new XMLHttpRequest();
        req.open(options.requestType, options.url, true);
        req.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        req.onreadystatechange = function() {
            if (req.readyState === 4) {
                if (req.status === 200 && 
                    typeof options.success === 'function'
                ) {
                    var response = req.responseText;
                    if (response && options.responseType === 'json') {
                        response = JSON.parse(response);
                    }
                    options.success(response);
                } else if (typeof options.error === 'function') {
                    options.error(req.responseText, req.statusText);
                }
            }
        }

        if (typeof options.data === 'string') {
            req.send(options.data);
        } else {
            req.send();
        }
    };
});

}());
