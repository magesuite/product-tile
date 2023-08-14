<?php

namespace MageSuite\ProductTile\Cache;

interface CacheKeyModel
{
    /**
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment);
}
