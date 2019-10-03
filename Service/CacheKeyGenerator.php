<?php

namespace MageSuite\ProductTile\Service;

class CacheKeyGenerator implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    const CACHE_KEY_PREFIX = 'product_tile';

    protected $flatChilds = null;
    protected $childsWithCacheKeyGenerators = null;

    protected $areaCustomizationStatus = [];

    public function generate(\MageSuite\ProductTile\Block\Tile $tile)
    {
        $product = $tile->getProductEntity();

        if (!$product) {
            return [];
        }

        $cacheKey = $this->getChildsCacheKeys($tile->getChilds(), $tile);

        if(!empty($tile->getSection()) and $this->wasAreaCustomized($tile->getChilds(), $tile)) {
            $cacheKey = array_merge([$tile->getSection()], $cacheKey);
        }

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
        $childs = $this->getChildsWithCacheKeyGenerators($blocks, $tile);

        $cacheKeys = [];

        if (!$childs or empty($childs)) {
            return [];
        }

        foreach ($childs as $child) {
            if ($child instanceof \MageSuite\ProductTile\Block\Tile\Fragment) {
                $child->setTile($tile);

                $cacheKeys = array_merge($cacheKeys, $child->getCacheKeyInfo());
            }
        }

        return $cacheKeys;
    }

    protected function wasAreaCustomized($blocks, $tile) {
        $area = $tile->getSection();

        if(empty($area)) {
            return false;
        }

        if(isset($this->areaCustomizationStatus[$area])) {
            return $this->areaCustomizationStatus[$area];
        }

        $areasWithCustomFragments = array_unique($this->getChildsAreasConfiguration($blocks, $tile));
        $areaHasCustomConfig = isset($tile->getSections()[$area]) && !empty($tile->getSections()[$area]);

        $result = (in_array($area, $areasWithCustomFragments) || $areaHasCustomConfig);
        $this->areaCustomizationStatus[$area] = $result;

        return $result;
    }

    protected function getChildsAreasConfiguration($blocks, $tile) {
        $childs = $this->getFlatChilds($blocks, $tile);
        $sections = [];

        if (!$childs or empty($childs)) {
            return [];
        }

        foreach ($childs as $child) {
            if(!empty($child->getSupportedAreas()) and is_array($child->getSupportedAreas())) {
                $sections = array_merge($sections, $child->getSupportedAreas());
            }

            if(!empty($child->getUnsupportedAreas()) and is_array($child->getUnsupportedAreas())) {
                $sections = array_merge($sections, $child->getUnsupportedAreas());
            }
        }

        return $sections;
    }

    protected function getFlatChilds($blocks, $tile) {
        if($this->flatChilds == null) {
            $this->flatChilds = $this->getChilds($blocks, $tile);
        }

        return $this->flatChilds;
    }

    protected function getChildsWithCacheKeyGenerators($blocks, $tile) {
        if($this->childsWithCacheKeyGenerators == null) {
            $this->childsWithCacheKeyGenerators = [];

            $childs = $this->getFlatChilds($blocks, $tile);

            foreach($childs as $child) {
                if(!$child->getCacheKeyModel()) {
                    continue;
                }

                $this->childsWithCacheKeyGenerators[] = $child;
            }
        }

        return $this->childsWithCacheKeyGenerators;
    }

    public function getChilds($blocks, $tile)
    {
        $childs = [];

        if (!$blocks or empty($blocks)) {
            return [];
        }

        foreach ($blocks as $child) {
            if ($child instanceof \MageSuite\ProductTile\Block\Tile\Fragment) {
                $child->setTile($tile);
            }

            $childs = array_merge($childs, [$child]);
            $childs = array_merge($childs, $this->getChilds($child->getChilds(), $tile));
        }

        return $childs;
    }
}