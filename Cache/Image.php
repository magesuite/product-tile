<?php

namespace MageSuite\ProductTile\Cache;

class Image implements CacheKeyModel, \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \Magento\Eav\Model\Config $eavConfig;
    protected \Magento\Framework\App\Request\Http $request;
    protected \Magento\Swatches\Helper\Data $swatchHelperData;
    protected ?array $resultCache = null;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Swatches\Helper\Data $swatchHelperData
    )
    {
        $this->request = $request;
        $this->eavConfig = $eavConfig;
        $this->swatchHelperData = $swatchHelperData;
    }

    /**
     * For cache purposes we need to detect if filtering happened using a swatch attribute that could affect the url of
     * displayed product image. In such case tile should have separate cache key with filter values.
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment)
    {
        $product = $fragment->getProduct();

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
                $value = implode(',', $value);
            }

            $filterArray[] = hash('md5', sprintf('%s=%s', $code, $value));
        }

        return $filterArray;
    }

    private function canReplaceImageWithSwatch($attribute)
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
