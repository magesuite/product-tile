<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Test\Integration\Cache;

/**
 * @magentoAppIsolation enabled
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Framework\ObjectManagerInterface $objectManager;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    protected ?\Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository;
    protected ?\Magento\Framework\App\RequestInterface $request;
    protected ?\MageSuite\ProductTile\Cache\Image $cacheKeyGenerator;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->attributeRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductAttributeRepositoryInterface::class);
        $this->request = $this->objectManager->get(\Magento\Framework\App\RequestInterface::class);
        $this->cacheKeyGenerator = $this->objectManager->get(\MageSuite\ProductTile\Cache\Image::class);
    }

    /**
     * @magentoDataFixture Magento/Swatches/_files/configurable_product_visual_swatch_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/product_image.php
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('filteringConditionsDataProvider')]
    public function testFilterValuesAreAddedToCacheKeyWhenFilteredByAttributeThatCanChangeImage(bool $filterByVisualSwatch, bool $resultShouldBeEmpty): void
    {
        $this->updateAttributePreviewImageFlag('visual_swatch_attribute');

        if ($filterByVisualSwatch) {
            $this->addFiltersToRequest('visual_swatch_attribute', ['option 1']);
        } else {
            $this->request->setParams([]);
        }

        $fragment = $this->prepareTileFragment('configurable');

        if ($resultShouldBeEmpty) {
            $this->assertEquals([], $this->cacheKeyGenerator->getCacheKeyInfo($fragment));
        } else {
            $this->assertNotEmpty($this->cacheKeyGenerator->getCacheKeyInfo($fragment));
        }
    }

    /**
     * @magentoDataFixture Magento/Swatches/_files/configurable_product_visual_swatch_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/product_image.php
     */
    public function testImageVariationHashIsOrderIndependent(): void
    {
        $this->updateAttributePreviewImageFlag('visual_swatch_attribute');
        $this->addFiltersToRequest('visual_swatch_attribute', ['option 1', 'option 2']);
        $fragment = $this->prepareTileFragment('configurable');
        $cacheKey = $this->cacheKeyGenerator->getCacheKeyInfo($fragment);

        $this->assertNotEmpty($cacheKey);

        //change order of selected options and compare cache keys
        $this->addFiltersToRequest('visual_swatch_attribute', ['option 2', 'option 1']);

        //create new instance to reset internal cache
        $cacheKeyGenerator = $this->objectManager->create(\MageSuite\ProductTile\Cache\Image::class);
        $cacheKeyAfterChangingOrder = $cacheKeyGenerator->getCacheKeyInfo($fragment);

        $this->assertNotEmpty($cacheKey);
        $this->assertEquals($cacheKey, $cacheKeyAfterChangingOrder);
    }

    /**
     * @magentoDataFixture Magento/Swatches/_files/configurable_product_visual_swatch_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/product_image.php
     * @magentoConfigFixture current_store product_tile/cache/limit_image_variations_in_cache_key 1
     */
    public function testEmptyCacheKeyForSimpleWhenLimitImageHashVariationEnabled(): void
    {
        $this->updateAttributePreviewImageFlag('visual_swatch_attribute');
        $this->addFiltersToRequest('visual_swatch_attribute', ['option 1']);
        $fragment = $this->prepareTileFragment('simple_option_1');

        $this->assertEquals([], $this->cacheKeyGenerator->getCacheKeyInfo($fragment));
    }

    /**
     * @magentoDataFixture Magento/Swatches/_files/configurable_product_visual_swatch_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/product_image.php
     * @magentoConfigFixture current_store product_tile/cache/limit_image_variations_in_cache_key 0
     */
    public function testEmptyCacheKeyForSimpleWhenLimitImageHashVariationDisabled(): void
    {
        $this->updateAttributePreviewImageFlag('visual_swatch_attribute');
        $this->addFiltersToRequest('visual_swatch_attribute', ['option 1']);
        $fragment = $this->prepareTileFragment('simple_option_1');

        $this->assertNotEmpty($this->cacheKeyGenerator->getCacheKeyInfo($fragment));
    }

    protected function prepareTileFragment(string $sku): \MageSuite\ProductTile\Block\Tile\Fragment
    {
        $tile = $this->objectManager->create(\MageSuite\ProductTile\Block\Tile::class);
        $tile->setProductEntity($this->productRepository->get($sku));

        $fragment = $this->objectManager->get(\MageSuite\ProductTile\Block\Tile\Fragment::class);
        $fragment->setTile($tile);

        return $fragment;
    }

    public static function filteringConditionsDataProvider(): array
    {
        return [
            'filter_by_attribute' => [
                'filterByVisualSwatch' => true,
                'resultShouldBeEmpty' => false
            ],
            'no_filters' => [
                'filterByVisualSwatch' => false,
                'resultShouldBeEmpty' => true
            ],
        ];
    }

    protected function updateAttributePreviewImageFlag(string $attributeCode): void
    {
        $attribute = $this->attributeRepository->get($attributeCode);
        $attribute->setData('update_product_preview_image', 1);
        $this->attributeRepository->save($attribute);
    }

    protected function addFiltersToRequest(string $attributeCode, array $optionLabels): void
    {
        $attribute = $this->attributeRepository->get($attributeCode);
        $options = [];

        foreach ($optionLabels as $optionLabel) {
            $options[] = $attribute->getSource()->getOptionId($optionLabel);
        }

        $this->request->setParams(
            [$attributeCode => $options]
        );
    }
}
