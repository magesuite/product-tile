<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment\AddTo;

class Compare extends \MageSuite\ProductTile\Block\Tile\Fragment {
    /**
     * @return \Magento\Catalog\Helper\Product\Compare
     * @since 101.0.1
     */
    public function getCompareHelper()
    {
        return $this->_compareProduct;
    }
}

