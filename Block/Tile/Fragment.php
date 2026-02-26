<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Block\Tile;

class Fragment extends \Magento\Catalog\Block\Product\ListProduct
{
    protected ?\MageSuite\ProductTile\Block\Tile $tile = null;

    public function getTile(): ?\MageSuite\ProductTile\Block\Tile
    {
        return $this->tile;
    }

    public function setTile(\MageSuite\ProductTile\Block\Tile $tile): self
    {
        $this->tile = $tile;

        return $this;
    }

    public function getProduct(): ?\Magento\Catalog\Api\Data\ProductInterface
    {
        return $this->tile->getProductEntity();
    }

    public function shouldBeRendered(): bool
    {
        if (!$this->getTile()) {
            return false;
        }

        if ($this->getUnsupportedAreas() != null and $this->getTile()->getAreas()) {
            if ($this->isInOneOfAreas($this->getUnsupportedAreas())) {
                return false;
            }
        }

        if ($this->getSupportedAreas() == null) {
            return true;
        }

        if (empty($this->getTile()->getAreas())) {
            return false;
        }

        if (!$this->isInOneOfAreas($this->getSupportedAreas())) {
            return false;
        }

        return true;
    }

    public function _toHtml(): string
    {
        if (!$this->shouldBeRendered()) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function _beforeToHtml(): self
    {
        return $this;
    }

    public function getSectionData(string $key): null|string|bool
    {
        return $this->tile->getSectionData($key);
    }

    public function getIdentities(): array
    {
        return [];
    }

    public function getWishlistHelper(): \Magento\Wishlist\Helper\Data
    {
        return $this->_wishlistHelper;
    }

    protected function isInOneOfAreas(array $areas): bool
    {
        foreach ($areas as $area) {
            if (in_array($area, $this->getTile()->getAreas())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Performance optimization to not execute native blocks observers
     * @return string
     */
    public function toHtml(): string
    {
        return $this->_loadCache();
    }

    public function getCacheKeyInfo(): array
    {
        if (!$this->getTile()) {
            return [];
        }

        if (!$this->getCacheKeyModel()) {
            return [];
        }

        return $this->getCacheKeyModel()->getCacheKeyInfo($this);
    }

    public function getAddToCartUrl($product, $additional = []): string //phpcs:ignore
    {
        $additional = array_merge($additional, ['from_tile' => true]);

        return parent::getAddToCartUrl($product, $additional);
    }
}
