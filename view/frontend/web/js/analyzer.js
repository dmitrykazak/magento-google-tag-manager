define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data'
], function ($, _, customerData) {
    'use strict';

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
        if (!_.isObject(dataObject)) {
            return;
        }

        if (_.has(dataObject, 'cart') && !_.isEmpty(dataObject.cart)) {
            initCartDataLayer('addToCart', 'add', dataObject.cart);
        }

        if (_.has(dataObject, 'removeCart') && !_.isEmpty(dataObject.removeCart)) {
            initCartDataLayer('removeFromCart', 'remove', dataObject.removeCart);
        }
    });
});