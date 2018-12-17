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
        if($this->getSupportedArea() == null) {
            return true;
        }

        if(empty($this->getTile()->getAreas())) {
            return false;
        }

        if(!in_array($this->getSupportedArea(), $this->getTile()->getAreas())) {
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
}