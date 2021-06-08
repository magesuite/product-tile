<?php

namespace MageSuite\ProductTile\Test\Integration\Plugin\Catalog\Helper\Product\Compare;

class RemoveUencFromAddToCompareParametersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    protected $compareHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Model\ProductRepository::class);
        $this->compareHelper = $this->objectManager->get(\Magento\Catalog\Helper\Product\Compare::class);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testUencIsRemovedFromAddToCompareParams() {
        $product = $this->productRepository->get('simple');

        $params = json_decode($this->compareHelper->getPostDataParams($product), true);

        $this->assertFalse(isset($params['data']['uenc']));
    }
}
