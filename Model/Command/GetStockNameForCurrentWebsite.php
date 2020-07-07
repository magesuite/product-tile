<?php

namespace MageSuite\ProductTile\Model\Command;

class GetStockNameForCurrentWebsite
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\InventorySalesApi\Api\StockResolverInterface
     */
    protected $stockResolver;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\InventorySalesApi\Api\StockResolverInterface $stockResolver
    ) {
        $this->storeManager = $storeManager;
        $this->stockResolver = $stockResolver;
    }

    public function execute(): string
    {
        $websiteCode = $this->storeManager->getWebsite()->getCode();

        $stock = $this->stockResolver->execute(\Magento\InventorySalesApi\Api\Data\SalesChannelInterface::TYPE_WEBSITE, $websiteCode);

        return $stock->getName();
    }
}
