<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Cache;

class Image implements CacheKeyModel, \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected ?array $resultCache = null;

    public function __construct(
        protected \Magento\Framework\App\Request\Http $request,
        protected \Magento\Eav\Model\Config $eavConfig,
        protected \Magento\Swatches\Helper\Data $swatchHelperData,
        protected \MageSuite\ProductTile\Helper\Configuration $configuration
    ) {
    }

    /**
     * For cache purposes we need to detect if filtering happened using a swatch attribute that could affect the url of
     * displayed product image. In such case tile should have separate cache key with filter values.
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment): array
    {
        $product = $fragment->getProduct();

        if (
            $this->configuration->isCacheKeyEnabledForVariantImage() &&
            $product->getTypeId() !== \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE
        ) {
            return [];
        }

        if ($this->resultCache === null) {
            $this->resultCache = $this->getFiltersValuesThatCanChangeImage($product);
        }

        return $this->resultCache;
    }

    protected function getFiltersValuesThatCanChangeImage(\Magento\Catalog\Model\Product $product): array
    {
        $attributes = $this->eavConfig->getEntityAttributes(\Magento\Catalog\Model\Product::ENTITY, $product);
        $request = $this->request->getParams();

        $filterArray = [];

        foreach ($request as $code => $value) {
            if (!isset($attributes[$code])) {
                continue;
            }

            $attribute = $attributes[$code];

            if (!$this->canReplaceImageWithSwatch($attribute)) {
                continue;
            }

            if (is_array($value)) {
                sort($value, SORT_STRING);
                $value = implode(',', $value);
            }

            $filterArray[] = hash('md5', sprintf('%s=%s', $code, $value));
        }

        return $filterArray;
    }

    protected function canReplaceImageWithSwatch(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute): bool
    {
        if (!$this->swatchHelperData->isSwatchAttribute($attribute)) {
            return false;
        }

        if (!$attribute->getUsedInProductListing()
            || !$attribute->getIsFilterable()
            || !$attribute->getData('update_product_preview_image')
        ) {
            return false;
        }

        return true;
    }
}
