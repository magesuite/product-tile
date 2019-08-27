<?php

namespace MageSuite\ProductTile\Block;

class Tile extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_template = 'MageSuite_ProductTile::tile.phtml';

    protected static $globalData = [];

    public function __construct(\Magento\Catalog\Block\Product\Context $context, array $data = [])
    {
        self::$globalData = array_merge(self::$globalData, $data);

        parent::__construct($context, $data);

        $this->_data = array_merge($this->_data, self::$globalData);
    }

    public function render(\Magento\Catalog\Model\Product $product) {
        $this->unsetData('CACHE_KEY_INFO');
        $this->setProductEntity($product);

        return $this->toHtml();
    }

    public function getChilds() {
        $childs = [];

        $layout = $this->getLayout();

        foreach ($layout->getChildBlocks('product.tile') as $child) {
            $childs[] = $child;
        }

        return $childs;
    }

    protected function _toHtml()
    {
        if(!$this->getProductEntity()) {
            return '';
        }

        return parent::_toHtml();
    }

    public function getSectionData($key) {
        $area = $this->getSection();

        if(isset($this->_data['sections'][$area][$key])) {
            return $this->_data['sections'][$area][$key];
        }

        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return null;
    }

    public function getIdentities() {
        if($this->getProductEntity()) {
            return $this->getProductEntity()->getIdentities();
        }

        return [];
    }

    public function getCacheKeyInfo()
    {
        if($this->hasData('CACHE_KEY_INFO')) {
            return $this->getData('CACHE_KEY_INFO');
        }

        $product = $this->getProductEntity();

        if(!$product) {
            return [];
        }

        /** @var \MageSuite\ProductTile\Service\CacheKeyGenerator $cacheKeyGenerator */
        $cacheKeyGenerator = $this->getCacheKeyGenerator();

        $cacheKey = $cacheKeyGenerator->generate($this);

        $this->setData('CACHE_KEY_INFO', $cacheKey);

        return $cacheKey;
    }
}