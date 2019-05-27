define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data'
], function ($, _, customerData) {
    'use strict';

    let analyzerData = customerData.get('analyzer-data');

    analyzerData.subscribe(function (dataObject) {
        console.log(dataObject);
    });
});