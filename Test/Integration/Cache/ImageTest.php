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
//    public function testItReturnsImageUrlForCacheKey()
//    {
//       $fragment = $this->getFragmentByProduct('simple');
//
//       $result = $this->imageCacheKeyGenerator->getCacheKeyInfo($fragment);
//
//       // pleaceholder here is on purpose since tests cannot access images definitions from etc/view.xml of theme
//       $expectedUrl = $this->prepareRegexUrl('http://localhost/pub/static/version([0-9]+?)/_view/en_US/Magento_Catalog/images/product/placeholder/.jpg');
//
//       $this->assertRegExp($expectedUrl, $result[0]);
//    }

    protected function prepareRegexUrl($url) {
        $url = str_replace('/', '\/', $url);
        return sprintf('/%s/', $url);
    }
}