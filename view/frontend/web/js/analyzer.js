define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data'
], function ($, _, customerData) {
    'use strict';

    let lastCart = {};

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

    let analyzerData = customerData.get('analyzer-data');

    analyzerData.subscribe(function (dataObject) {
        if (_.isObject(dataObject) && _.has(dataObject, 'cart')) {
            if (_.isEmpty(lastCart)) {
                lastCart = dataObject.cart;
            }

            initCartDataLayer('addToCart', 'add', dataObject.cart);
        }

        initCartDataLayer('removeFromCart', 'remove', );
    });
});