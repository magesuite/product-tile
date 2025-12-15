<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Service;

class CacheKeyGenerator implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public const CACHE_KEY_PREFIX = 'product_tile';

    protected ?array $flatChilds = null;
    protected ?array $childsWithCacheKeyGenerators = null;

    protected array $areaCustomizationStatus = [];

    public function generate(\MageSuite\ProductTile\Block\Tile $tile): array
    {
        $product = $tile->getProductEntity();

        if (!$product) {
            return [];
        }

        $cacheKey = $this->getChildsCacheKeys($tile->getChilds(), $tile);

        if (!empty($tile->getSection()) and $this->wasAreaCustomized($tile->getChilds(), $tile)) {
            $cacheKey = array_merge([$tile->getSection()], $cacheKey);
        }

        if ($tile->hasData('special_description')) {
            $cacheKey[] = $tile->getData('special_description');
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
     * @param \Magento\Framework\View\Element\BlockInterface[] $blocks
     * @param \Magento\Framework\View\Element\BlockInterface $tile
     * @return array
     */
    public function getChildsCacheKeys(array $blocks, \Magento\Framework\View\Element\BlockInterface $tile): array
    {
        $childs = $this->getChildsWithCacheKeyGenerators($blocks, $tile);

        $cacheKeys = [];

        if (!$childs or empty($childs)) {
            return [];
        }

        foreach ($childs as $child) {
            if ($child instanceof \MageSuite\ProductTile\Block\Tile\Fragment) {
                $child->setTile($tile);

                $cacheKeys[] = $child->getCacheKeyInfo();
            }
        }

        return array_merge([], ...$cacheKeys);
    }

    protected function wasAreaCustomized(array $blocks, \Magento\Framework\View\Element\BlockInterface $tile): bool
    {
        $area = $tile->getSection();

        if (empty($area)) {
            return false;
        }

        if (isset($this->areaCustomizationStatus[$area])) {
            return $this->areaCustomizationStatus[$area];
        }

        $areasWithCustomFragments = array_unique($this->getChildsAreasConfiguration($blocks, $tile));
        $areaHasCustomConfig = isset($tile->getSections()[$area]) && !empty($tile->getSections()[$area]);

        $result = (in_array($area, $areasWithCustomFragments) || $areaHasCustomConfig);
        $this->areaCustomizationStatus[$area] = $result;

        return $result;
    }

    protected function getChildsAreasConfiguration(array $blocks, \Magento\Framework\View\Element\BlockInterface $tile): array
    {
        $childs = $this->getFlatChilds($blocks, $tile);
        $sections = [];

        if (!$childs or empty($childs)) {
            return [];
        }

        foreach ($childs as $child) {
            if (!empty($child->getSupportedAreas()) and is_array($child->getSupportedAreas())) {
                $sections[] = $child->getSupportedAreas();
            }

            if (!empty($child->getUnsupportedAreas()) and is_array($child->getUnsupportedAreas())) {
                $sections[] = $child->getUnsupportedAreas();
            }
        }

        return array_merge([], ...$sections);
    }

    protected function getFlatChilds(array $blocks,  \Magento\Framework\View\Element\BlockInterface $tile): array
    {

        if ($this->flatChilds == null) {
            $this->flatChilds = $this->getChilds($blocks, $tile);
        }

        return $this->flatChilds;
    }

    protected function getChildsWithCacheKeyGenerators(array $blocks, \Magento\Framework\View\Element\BlockInterface $tile): array
    {
        if ($this->childsWithCacheKeyGenerators === null) {

            $this->childsWithCacheKeyGenerators = [];

            $childs = $this->getFlatChilds($blocks, $tile);

            foreach ($childs as $child) {
                if (!$child->getCacheKeyModel()) {
                    continue;
                }

                $this->childsWithCacheKeyGenerators[] = $child;
            }
        }

        return $this->childsWithCacheKeyGenerators;
    }

    public function getChilds(?array $blocks, \Magento\Framework\View\Element\BlockInterface $tile): array
    {
        $childs = [];

        if (!$blocks or empty($blocks)) {
            return [];
        }

        foreach ($blocks as $child) {
            if ($child instanceof \MageSuite\ProductTile\Block\Tile\Fragment) {
                $child->setTile($tile);
            }

            $childs[] = [$child];
            $childs[] = $this->getChilds($child->getChilds(), $tile);
        }

        return array_merge([], ...$childs);
    }
}
