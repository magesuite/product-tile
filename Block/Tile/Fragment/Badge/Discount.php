<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment\Badge;

class Discount implements \MageSuite\ProductTile\Block\Tile\Fragment\BadgeInterface
{
    protected $discounts = [];

    /**
     * @var \MageSuite\Frontend\Helper\Product
     */
    protected $productHelper;

    public function __construct(\MageSuite\Frontend\Helper\Product $productHelper)
    {
        $this->productHelper = $productHelper;
    }

    /**
     * @return boolean
     */
    public function isVisible(\MageSuite\ProductTile\Block\Tile $tile)
    {
        return $this->getValue($tile) > 0;
    }

    /**
     * @return string
     */
    public function getValue(\MageSuite\ProductTile\Block\Tile $tile)
    {
        $productId = $tile->getProductEntity()->getId();

        if(!isset($this->discounts[$productId])) {
            $this->discounts[$productId] = $this->productHelper->getSalePercentage($tile->getProductEntity());
        }

        return $this->discounts[$productId];
    }

    /**
     * @return string
     */
    public function getCssModifier(\MageSuite\ProductTile\Block\Tile $tile) {
        return '';
    }
}