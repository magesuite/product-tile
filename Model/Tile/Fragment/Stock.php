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
        if($product->getTypeId() != \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE){
            return $product->isSaleable();
        }

        $salableQty = $this->getSalableQuantityDataBySku->execute($product->getSku());

        return $product->isSaleable() && $salableQty[0]['qty'] > 0;


    }
}