<?php

namespace MageSuite\ProductTile\Plugin\Checkout\Helper\Cart;

/**
 * Any tile rendered in cart action contains in_cart/1 part in URL which breaks AJAX add to cart functionality
 * That part of URL is removed when tile is rendered in cart page
 */
class RemoveInCartParameterFromAddToCart
{
    /**
     * @param \Magento\Checkout\Helper\Cart $subject
     * @param $result
     * @param $product
     * @param array $additional
     * @return mixed
     */
    public function afterGetAddUrl(\Magento\Checkout\Helper\Cart $subject, $result, $product, $additional = []) {
        if($additional['from_tile'] === true) {
            $result = str_replace('/in_cart/1', '', $result);
            $result = str_replace('/from_tile/1', '', $result);
        }

        return $result;
    }
}