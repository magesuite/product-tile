<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Test\Integration\Model\Tile\Fragment;

class StockTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\Catalog\Model\ProductRepository $productRepository;

    protected \MageSuite\ProductTile\Model\Tile\Fragment\Stock $stockFragment;

    public function setUp(): void
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->productRepository = $objectManager->get(\Magento\Catalog\Model\ProductRepository::class);
        $this->stockFragment = $objectManager->get(\MageSuite\ProductTile\Model\Tile\Fragment\Stock::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_ProductTile::Test/Integration/_files/product.php
     */
    public function testItReturnsCorrectFlagForDefaultStock(): void
    {
        $productSku = 'product';
        $product = $this->productRepository->get($productSku);

        $this->assertFalse($this->stockFragment->isSaleable($product));
    }
}
