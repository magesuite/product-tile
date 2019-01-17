<?php

namespace MageSuite\ProductTile\Test\Integration\Cache;

class ImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \MageSuite\ProductTile\Cache\Image
     */
    protected $imageCacheKeyGenerator;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Model\ProductRepository::class);
        $this->imageCacheKeyGenerator = $this->objectManager->get(\MageSuite\ProductTile\Cache\Image::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_with_image.php
     */
    public function testItReturnsImageUrlForCacheKey()
    {
        $tile = $this->objectManager->create(\MageSuite\ProductTile\Block\Tile::class);

        $product = $this->productRepository->get('simple');

        $tile->setProductEntity($product);

        $fragment = $this->objectManager->get(\MageSuite\ProductTile\Block\Tile\Fragment::class);
        $fragment->setTile($tile);

        $result = $this->imageCacheKeyGenerator->getCacheKeyInfo($fragment);

        print_r($result);

        $this->assertEquals([], $result);
    }
}