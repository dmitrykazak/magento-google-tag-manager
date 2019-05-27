define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data'
], function ($, _, customerData) {
    'use strict';

    function initBefore() {
        window.dataLayer = window.dataLayer || [];
    }

    function initDataLayer(event, action, product) {
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
        console.log(dataObject.cart);
        initDataLayer('addToCart', 'add', dataObject.cart)
    });
});