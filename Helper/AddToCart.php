<?php

namespace MageSuite\ProductTile\Helper;

class AddToCart extends  \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIGURABLE_TYPES = [
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
        \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE,
        \Magento\Bundle\Model\Product\Type::TYPE_CODE
    ];

    public function canBeConfigured(\Magento\Catalog\Model\Product $product) {
        return in_array($product->getTypeId(), self::CONFIGURABLE_TYPES);
    }
}