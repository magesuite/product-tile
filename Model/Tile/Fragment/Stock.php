<?php

namespace MageSuite\ProductTile\Model\Tile\Fragment;

class Stock implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku
     */
    protected $getSalableQuantityDataBySku;

    public function __construct(\Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku)
    {
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
    }

    public function isSaleable($product)
    {
        $salableQty = $this->getSalableQuantityDataBySku->execute($product->getSku());

        return $product->isSaleable() && $salableQty[0]['qty'];


    }
}