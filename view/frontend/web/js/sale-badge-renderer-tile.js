define([
    'jquery',
    'underscore',
    'jquery-ui-modules/widget',
    'Magento_Catalog/js/product/view/sale-badge-renderer'
], function($, _) {
    'use strict';

    $.widget('magesuite.saleBadgeRenderer', $.magesuite.saleBadgeRenderer, {
        options: {
            discountBadgeSelector: '.cs-product-tile__badge--discount',
            discountBadgeValueSelector:
                '.cs-product-tile__badge-discount-value',
            discountBadgeTextSelector: '.cs-product-tile__badge-discount-text',
            discountsList: {},
            productsIdsSelector: '[data-role^=swatch-option-]',
            attributesSelector: '.swatch-attribute'
        }
    });

    return $.magesuite.saleBadgeRenderer;
});
