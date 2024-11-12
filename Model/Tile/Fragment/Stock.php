<?php

namespace MageSuite\ProductTile\Model\Tile\Fragment;

class Stock implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \MageSuite\ProductTile\Model\Command\GetStockNameForCurrentWebsite $getStockNameForCurrentWebsite;
    protected \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku;
    protected ?string $currentStockName = null;

    public function __construct(
        \MageSuite\ProductTile\Model\Command\GetStockNameForCurrentWebsite $getStockNameForCurrentWebsite,
        \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku
    ) {
        $this->getStockNameForCurrentWebsite = $getStockNameForCurrentWebsite;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
    }

    public function isSaleable($product)
    {
        if ($product->getTypeId() != \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            return $product->isSaleable();
        }

        if (!$product->isSaleable()) {
            return false;
        }

        if ($product->hasData('quantity')) {
            return (float)$product->getData('quantity') > 0;
        }

        $productQty = $this->getQtyForCurrentStock($product->getSku());

        return $productQty > 0;
    }

    public function getQtyForCurrentStock($sku)
    {
        $salableQtys = $this->getSalableQuantityDataBySku->execute($sku);

        if (empty($salableQtys)) {
            return null;
        }

        $currentStockName = $this->getCurrentStockName();

        foreach ($salableQtys as $salableQty) {
            if ($salableQty['stock_name'] == $currentStockName) {
                return $salableQty['qty'];
            }
        }

        return null;
    }

    protected function getCurrentStockName()
    {
        if ($this->currentStockName == null) {
            $this->currentStockName = $this->getStockNameForCurrentWebsite->execute();
        }

        return $this->currentStockName;
    }
}
