define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data',
], function ($, _, storage) {
    'use strict';

    let lastStateCart = [];

    function initBefore() {
        window.dataLayer = window.dataLayer || [];
    }

    function initCartDataLayer(event, action, product) {
        initBefore();

        dataLayer.push({
            'event': event,
            'ecommerce': {
                [action]: {
                    'products': product
                }
            }
        });
    }

    function dataLayerPush(data) {
        dataLayer.push(data);
    }

    /**
     * @param {Object} config
     */
    return function (config) {
        initBefore();

        const productId = $('input[name="product"]').val();

        $.ajax({
            type: 'POST',
            data: {id: productId, currentUrl: config.currentUrl, refererUrl: config.refererUrl},
            url: config.ajaxUrl,
            success: (result, status) => {
                if (_.isArray(result) && result.length > 0) {
                    result.forEach((item) => {
                        dataLayerPush(item);
                    });
                }
            },
            dataType: 'json'
        });

        if (_.has(config, 'dataLayer') && _.isArray(config.dataLayer) && config.dataLayer.length > 0) {
            config.dataLayer.forEach((item) => {
                dataLayerPush(item);
            });
        }

        let analyzerData = storage.get('analyzer-data');

        analyzerData.subscribe((dataObject) => {
            if (!_.isObject(dataObject)) {
                return;
            }

            if (_.has(dataObject, 'cart') && !_.isEqual(lastStateCart, dataObject.cart)) {
                initCartDataLayer('addToCart', 'add', dataObject.cart);

                if (_.has(dataObject, 'removeCart') && _.isArray(dataObject.removeCart) && dataObject.removeCart.length > 0) {
                    initCartDataLayer('removeFromCart', 'remove', dataObject.removeCart);
                }

                lastStateCart = dataObject.cart;
            }

            if (_.has(dataObject, 'checkoutSteps') && _.isArray(dataObject.checkoutSteps) && dataObject.checkoutSteps.length > 0) {
                _.each(dataObject.checkoutSteps, dataLayerPush);
            }
        });
    };
});