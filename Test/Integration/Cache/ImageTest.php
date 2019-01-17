<?php

namespace MageSuite\ProductTile\Test\Integration\Cache;

class ImageTest extends AbstractCacheTest
{
    /**
     * @var \MageSuite\ProductTile\Cache\Image
     */
    protected $imageCacheKeyGenerator;

    public function setUp()
    {
        parent::setUp();

        $this->imageCacheKeyGenerator = $this->objectManager->get(\MageSuite\ProductTile\Cache\Image::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_with_image.php
     */
    public function testItReturnsImageUrlForCacheKey()
    {
       $fragment = $this->getFragmentByProduct('simple');

        $result = $this->imageCacheKeyGenerator->getCacheKeyInfo($fragment);

        // pleaceholder here is on purpose since tests cannot access images definitions from etc/view.xml of theme
        $this->assertEquals(['http://localhost/pub/static/version1547712031/_view/en_US/Magento_Catalog/images/product/placeholder/.jpg'], $result);
    }
}