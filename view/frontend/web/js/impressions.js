define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    function initImpressions(action, product) {
        dataLayer.push({
            'ecommerce': {
                [action]: product
            }
        });
    }

    /**
     * @param {Object} config
     */
    return function (config) {
        if (_.has(config, 'impressions') && _.isArray(config.impressions) && config.impressions.length > 0) {
            initImpressions('impressions', config.impressions);
        }
    }
});
