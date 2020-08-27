<?php

namespace MageSuite\ProductTile\Test\Integration\Cache;

abstract class AbstractCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Model\ProductRepository::class);
    }

    public function getFragmentByProduct($sku) {
        $product = $this->productRepository->get($sku);

        $tile = $this->objectManager->create(\MageSuite\ProductTile\Block\Tile::class);
        $tile->setProductEntity($product);
        $tile->setViewMode('grid');

        $fragment = $this->objectManager->get(\MageSuite\ProductTile\Block\Tile\Fragment::class);
        $fragment->setTile($tile);

        return $fragment;
    }
}
