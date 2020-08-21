<?php

namespace MageSuite\ProductTile\Test\Integration\Model\Tile\Fragment;

class StockTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \MageSuite\ProductTile\Model\Tile\Fragment\Stock
     */
    protected $stockFragment;

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
     * @magentoDataFixture loadProduct
     */
    public function testItReturnsCorrectFlagForDefaultStock()
    {
        $productSku = 'product';
        $product = $this->productRepository->get($productSku);

        $this->assertFalse($this->stockFragment->isSaleable($product));
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProduct
     * @magentoDataFixture loadAdditionalStockAndSource
     */
//    public function testItReturnsCorrectStockStatus()
//    {
//        $productSku = 'product';
//        $product = $this->productRepository->get($productSku);
//
//        $this->assertTrue($this->stockFragment->isSaleable($product));
//    }

    public static function loadAdditionalStockAndSource()
    {
        require __DIR__ . '/../../../_files/additional_stock_and_source.php';
    }

    public static function loadAdditionalStockAndSourceRollback()
    {
        require __DIR__ . '/../../../_files/additional_stock_and_source_rollback.php';
    }

    public static function loadProduct()
    {
        require __DIR__ . '/../../../_files/product.php';
    }

    public static function loadProductRollback()
    {
        require __DIR__ . '/../../../_files/product_rollback.php';
    }


}
