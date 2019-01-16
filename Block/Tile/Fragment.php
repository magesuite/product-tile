<?php

namespace MageSuite\ProductTile\Block\Tile;

class Fragment extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var \MageSuite\ProductTile\Block\Tile
     */
    protected $tile;

    public function getTile()
    {
        return $this->tile;
    }

    /**
     * @param mixed $tile
     */
    public function setTile($tile)
    {
        $this->tile = $tile;

        return $this;
    }

    public function getProduct() {
        return $this->tile->getProductEntity();
    }

    public function shouldBeRendered() {
        if($this->getUnsupportedAreas() != null and $this->getTile()->getAreas()) {
            if($this->isInOneOfAreas($this->getUnsupportedAreas())) {
                return false;
            }
        }

        if($this->getSupportedAreas() == null) {
            return true;
        }

        if(empty($this->getTile()->getAreas())) {
            return false;
        }

        if(!$this->isInOneOfAreas($this->getSupportedAreas())) {
            return false;
        }

        return true;
    }

    public function _toHtml()
    {
        if(!$this->shouldBeRendered()) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function _beforeToHtml()
    {
        return $this;
    }

    public function getSectionData($key) {
        return $this->tile->getSectionData($key);
    }

    public function getIdentities()
    {
        return [];
    }

    public function getWishlistHelper() {
        return $this->_wishlistHelper;
    }

    protected function isInOneOfAreas($areas) {
        foreach($areas as $area) {
            if(in_array($area, $this->getTile()->getAreas())) {
                return true;
            }
        }

        return false;
    }

    public function getCacheKeyInfo()
    {
        if(!$this->getTile()) {
            return [];
        }

        if(!$this->getCacheKeyModel()) {
            return [];
        }

        return $this->getCacheKeyModel()->getCacheKeyInfo($this);
    }
}