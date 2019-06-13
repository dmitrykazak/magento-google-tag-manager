define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    let impressionStorage = [];

    function initImpressions(action, product) {
        dataLayer.push({
            'ecommerce': {
                [action]: product
            }
        });
    }

    function initClickImpression(action, product) {
        dataLayer.push({
            'event': 'productClick',
            'ecommerce': {
                'click' : {
                    'actionField': {'list': action},
                    'products': product
                }
            }
        });
    }

    /**
     * @param {Object} config
     */
    return function (config) {
        if (_.isArray(config.impressions) && config.impressions.length > 0) {
            initImpressions('impressions', config.impressions);

            impressionStorage.push(config.impressions)
        }
    }
});
