<?php

namespace MageSuite\ProductTile\Block\Tile;

class Container extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_ProductTile::container.phtml';

    /**
     * @var \MageSuite\ProductTile\Block\Tile
     */
    protected $tile;

    public function getChilds() {
        $childs = [];

        $layout = $this->getLayout();

        $nameInLayout = $this->getNameInLayout();

        foreach ($layout->getChildBlocks($nameInLayout) as $child) {
            $childs[] = $child;
        }

        return $childs;
    }

    /**
     * @return mixed
     */
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

    /**
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct() {
        if(!$this->tile) {
            return null;
        }

        return $this->tile->getProductEntity();
    }

    public function getSectionData($key) {
        if(!$this->tile) {
            return null;
        }

        return $this->tile->getSectionData($key);
    }

    public function shouldBeRendered() {
        if(!$this->getTile()) {
            return false;
        }

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

    /**
     * Performance optimization to not execute native blocks observers
     * @return string
     */
    public function toHtml()
    {
        return $this->_loadCache();
    }

    public function _toHtml()
    {
        if(!$this->shouldBeRendered()) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function isInOneOfAreas($areas) {
        foreach($areas as $area) {
            if(in_array($area, $this->getTile()->getAreas())) {
                return true;
            }
        }

        return false;
    }
}
