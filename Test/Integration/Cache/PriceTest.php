<?php

namespace MageSuite\ProductTile\Test\Integration\Cache;

class PriceTest extends AbstractCacheTest
{
    /**
     * @var \MageSuite\ProductTile\Cache\Price
     */
    protected $priceCacheKeyGenerator;

    public function setUp(): void
    {
        parent::setUp();

        $this->priceCacheKeyGenerator = $this->objectManager->create(\MageSuite\ProductTile\Cache\Price::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_special_price.php
     * @magentoAdminConfigFixture product_tile/cache/include_customer_group_in_cache_key 1
     */
    public function testItReturnsPriceRelatedCacheKeyItems()
    {
       $fragment = $this->getFragmentByProduct('simple');

        $result = $this->priceCacheKeyGenerator->getCacheKeyInfo($fragment);

        $expected = [
            0 => 5.99,
            1 => '1',
            2 => 'USD',
            3 => 'grid',
            4 => 0,
        ];

        $this->assertCount(5, $result);

        foreach($expected as $index => $expectedValue) {
            $this->assertEquals($expectedValue, $result[$index], '', 2);
        }
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/product_special_price.php
     * @magentoAdminConfigFixture product_tile/cache/include_customer_group_in_cache_key 0
     */
    public function testItReturnsPriceRelatedCacheKeyItemsWithoutCustomerGroup()
    {
        $fragment = $this->getFragmentByProduct('simple');

        $result = $this->priceCacheKeyGenerator->getCacheKeyInfo($fragment);

        $expected = [
            0 => 5.99,
            1 => '1',
            2 => 'USD',
            3 => 'grid',
            4 => 0
        ];

        $this->assertCount(5, $result);

        foreach($expected as $index => $expectedValue) {
            $this->assertEquals($expectedValue, $result[$index], '', 2);
        }
    }
}
