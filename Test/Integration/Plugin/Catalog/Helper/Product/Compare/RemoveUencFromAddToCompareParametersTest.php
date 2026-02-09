<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Test\Integration\Plugin\Catalog\Helper\Product\Compare;

class RemoveUencFromAddToCompareParametersTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\App\ObjectManager $objectManager;
    protected ?\Magento\Catalog\Model\ProductRepository $productRepository;
    protected ?\Magento\Catalog\Helper\Product\Compare $compareHelper;

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
