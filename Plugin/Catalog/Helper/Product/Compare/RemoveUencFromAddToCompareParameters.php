<?php

namespace MageSuite\ProductTile\Plugin\Catalog\Helper\Product\Compare;

/**
 * Because tile is cached on any page, we need to remove uenc that contains url of first page where tile was
 * generated. Leaving it causes incorrect redirect after product is added to compare page on other URLs
 */
class RemoveUencFromAddToCompareParameters
{
    public function afterGetPostDataParams(\Magento\Catalog\Helper\Product\Compare $subject, $result) {
        $result = json_decode($result, true);

        if(isset($result['data']['uenc'])) {
            unset($result['data']['uenc']);
        }

        return json_encode($result);
    }
}
