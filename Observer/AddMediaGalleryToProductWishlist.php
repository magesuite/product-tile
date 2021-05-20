<?php

namespace MageSuite\ProductTile\Observer;

class AddMediaGalleryToProductWishlist implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $observer->getProductCollection();
        $productCollection->addMediaGalleryData();
    }
}
