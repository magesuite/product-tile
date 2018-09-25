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

    public function getProduct() {
        return $this->tile->getProductEntity();
    }

    public function getSectionData($key) {
        return $this->tile->getSectionData($key);
    }
}