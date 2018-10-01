<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment\Badge\PositiveIndicators;

class FreeShipping implements \MageSuite\ProductTile\Block\Tile\Fragment\BadgeInterface
{
    /**
     * @var \MageSuite\ProductPositiveIndicators\Helper\Product
     */
    protected $productHelper;

    public function __construct(\MageSuite\ProductPositiveIndicators\Helper\Product $productHelper)
    {
        $this->productHelper = $productHelper;
    }

    /**
     * @return boolean
     */
    public function isVisible(\MageSuite\ProductTile\Block\Tile $tile)
    {
        return $this->productHelper->showFreeShippingInProductTiles() and
            $this->productHelper->isFreeShipped($tile->getProductEntity());
    }

    /**
     * @return string
     */
    public function getValue(\MageSuite\ProductTile\Block\Tile $tile)
    {
        return '';
    }

    /**
     * @return string
     */
    public function getCssModifier(\MageSuite\ProductTile\Block\Tile $tile)
    {
        return '';
    }
}