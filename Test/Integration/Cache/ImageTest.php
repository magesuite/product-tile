<?php

namespace MageSuite\ProductTile\Test\Integration\Cache;

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
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Swatches/_files/configurable_product_visual_swatch_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/product_image.php
     * @dataProvider filteringConditionsDataProvider
     */
    public function testFilterValuesAreAddedToCacheKeyWhenFilteredByAttributeThatCanChangeImage(bool $filterByVisualSwatch, bool $resultShouldBeEmpty): void
    {
        $this->updateAttributePreviewImageFlag('visual_swatch_attribute');

        if($filterByVisualSwatch) {
            $this->addFilterToRequest('visual_swatch_attribute', 'option 1');
        } else {
            $this->request->setParams([]);
        }

        $tile = $this->objectManager->create(\MageSuite\ProductTile\Block\Tile::class);
        $tile->setProductEntity($this->productRepository->get('configurable'));

        $fragment = $this->objectManager->get(\MageSuite\ProductTile\Block\Tile\Fragment::class);
        $fragment->setTile($tile);

        if($resultShouldBeEmpty) {
            $this->assertEquals([], $this->cacheKeyGenerator->getCacheKeyInfo($fragment));
        } else {
            $this->assertNotEmpty($this->cacheKeyGenerator->getCacheKeyInfo($fragment));
        }
    }

    public static function filteringConditionsDataProvider() {
        return [
            'filter_by_attribute' => [
                'filter_by_visual_swatch' => true,
                'result_should_be_empty' => false
            ],
            'no_filters' => [
                'filter_by_visual_swatch' => false,
                'result_should_be_empty' => true
            ],
        ];
    }

    protected function updateAttributePreviewImageFlag(string $attributeCode): void
    {
        $attribute = $this->attributeRepository->get($attributeCode);
        $attribute->setData('update_product_preview_image', 1);
        $this->attributeRepository->save($attribute);
    }

    protected function addFilterToRequest(string $attributeCode, string $optionLabel): void
    {
        $attribute = $this->attributeRepository->get($attributeCode);
        $this->request->setParams(
            [$attributeCode => $attribute->getSource()->getOptionId($optionLabel)]
        );
    }
}
