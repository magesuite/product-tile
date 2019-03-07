<?php

namespace MageSuite\ProductTile\Service;

class CacheKeyGenerator implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    const CACHE_KEY_PREFIX = 'product_tile';

    public function generate(\MageSuite\ProductTile\Block\Tile $tile)
    {
        $product = $tile->getProductEntity();

        if (!$product) {
            return [];
        }

        $cacheKey = $this->getChildsCacheKeys($tile->getChilds(), $tile);

        $cacheKey = array_merge(
            [
                self::CACHE_KEY_PREFIX,
                $product->getId(),
            ],
            $cacheKey
        );

        return $cacheKey;
    }

    /**
     * Get CacheKeys from all defined fragments
     * @param $blocks
     * @param $tile
     * @return array
     */
    public function getChildsCacheKeys($blocks, $tile)
    {
        $cacheKeys = [];

        if (!$blocks or empty($blocks)) {
            return [];
        }

        foreach ($blocks as $child) {
            if ($child instanceof \MageSuite\ProductTile\Block\Tile\Fragment) {
                $child->setTile($tile);

                $cacheKeys = array_merge($cacheKeys, $child->getCacheKeyInfo());
            }

            $cacheKeys = array_merge($cacheKeys, $this->getChildsCacheKeys($child->getChilds(), $tile));
        }

        return $cacheKeys;
    }
}