<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment;

interface BadgeInterface extends \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @return boolean
     */
    public function isVisible(\MageSuite\ProductTile\Block\Tile $tile);

    /**
     * @return string
     */
    public function getValue(\MageSuite\ProductTile\Block\Tile $tile);

    /**
     * @return string
     */
    public function getCssModifier(\MageSuite\ProductTile\Block\Tile $tile);
}
