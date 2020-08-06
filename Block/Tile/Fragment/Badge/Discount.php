<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment\Badge;

class Discount implements \MageSuite\ProductTile\Block\Tile\Fragment\BadgeInterface
{
    /**
     * @var \MageSuite\Discount\Helper\Discount
     */
    protected $discountHelper;

    protected $discounts = [];

    public function __construct(\MageSuite\Discount\Helper\Discount $productHelper)
    {
        $this->discountHelper = $productHelper;
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
            $this->discounts[$productId] = $this->discountHelper->getSalePercentage($tile->getProductEntity());
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
