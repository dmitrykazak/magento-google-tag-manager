define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data',
], function ($, _, customerData) {
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

    function initImpressions(action, product) {
        initBefore();

        dataLayer.push({
            'ecommerce': {
                [action]: product
            }
        });
    }

    function dataLayerPush(data) {
        initBefore();

        dataLayer.push(data);
    }

    console.log(window.impressions);

    if (typeof window.impressions !== 'undefined') {
        initImpressions('impressions', window.impressions);
    }


    let analyzerData = customerData.get('analyzer-data');

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
});