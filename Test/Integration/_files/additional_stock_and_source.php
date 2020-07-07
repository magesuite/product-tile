<?php

$objectManager = Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$stockFactory = $objectManager->get(\Magento\InventoryApi\Api\Data\StockInterfaceFactory::class);
$salesChannelFactory = $objectManager->get(\Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory::class);
$dataObjectHelper = $objectManager->get(\Magento\Framework\Api\DataObjectHelper::class);
$stockRepository = $objectManager->get(\Magento\InventoryApi\Api\StockRepositoryInterface::class);

$sourceFactory = $objectManager->get(\Magento\InventoryApi\Api\Data\SourceInterfaceFactory::class);
$sourceRepository = $objectManager->get(\Magento\InventoryApi\Api\SourceRepositoryInterface::class);

$stockSourceLinksSave = $objectManager->get(\Magento\InventoryApi\Api\StockSourceLinksSaveInterface::class);
$stockSourceLinkFactory = $objectManager->get(\Magento\InventoryApi\Api\Data\StockSourceLinkInterfaceFactory::class);

$secondStockId = 10;
$secondStockName = 'second-stock';
$testSourceCodeName = 'test-source-code';

$stock = $stockFactory->create();

$dataObjectHelper->populateWithArray(
    $stock,
    [
        \Magento\InventoryApi\Api\Data\StockInterface::STOCK_ID => $secondStockId,
        \Magento\InventoryApi\Api\Data\StockInterface::NAME => $secondStockName,
    ],
    \Magento\InventoryApi\Api\Data\StockInterface::class
);

$salesChannel = $salesChannelFactory->create();
$salesChannel->setType('website');
$salesChannel->setCode('base');

$extensionAttributs = $stock->getExtensionAttributes();
$extensionAttributs->setSalesChannels([$salesChannel]);
$stock->setExtensionAttribute($extensionAttributs);

$stockRepository->save($stock);

$source = $sourceFactory->create();
$dataObjectHelper->populateWithArray(
    $source,
    [
        \Magento\InventoryApi\Api\Data\SourceInterface::SOURCE_CODE => $testSourceCodeName,
        \Magento\InventoryApi\Api\Data\SourceInterface::NAME => $testSourceCodeName,
        \Magento\InventoryApi\Api\Data\SourceInterface::CONTACT_NAME => 'source-contact-name',
        \Magento\InventoryApi\Api\Data\SourceInterface::EMAIL => 'source-email@test.com',
        \Magento\InventoryApi\Api\Data\SourceInterface::ENABLED => true,
        \Magento\InventoryApi\Api\Data\SourceInterface::DESCRIPTION => 'source-description',
        \Magento\InventoryApi\Api\Data\SourceInterface::LATITUDE => 11.123456,
        \Magento\InventoryApi\Api\Data\SourceInterface::LONGITUDE => 12.123456,
        \Magento\InventoryApi\Api\Data\SourceInterface::COUNTRY_ID => 'US',
        \Magento\InventoryApi\Api\Data\SourceInterface::REGION_ID => 10,
        \Magento\InventoryApi\Api\Data\SourceInterface::CITY => 'source-city',
        \Magento\InventoryApi\Api\Data\SourceInterface::STREET => 'source-street',
        \Magento\InventoryApi\Api\Data\SourceInterface::POSTCODE => 'source-postcode',
        \Magento\InventoryApi\Api\Data\SourceInterface::PHONE => 'source-phone',
        \Magento\InventoryApi\Api\Data\SourceInterface::FAX => 'source-fax',
        \Magento\InventoryApi\Api\Data\SourceInterface::USE_DEFAULT_CARRIER_CONFIG => 0,
        \Magento\InventoryApi\Api\Data\SourceInterface::USE_DEFAULT_CARRIER_CONFIG => false,
        \Magento\InventoryApi\Api\Data\SourceInterface::CARRIER_LINKS => [],
    ],
    \Magento\InventoryApi\Api\Data\SourceInterface::class
);
$sourceRepository->save($source);


$linkData = [
    \Magento\InventoryApi\Api\Data\StockSourceLinkInterface::STOCK_ID => $secondStockId,
    \Magento\InventoryApi\Api\Data\StockSourceLinkInterface::SOURCE_CODE => $testSourceCodeName,
    \Magento\InventoryApi\Api\Data\StockSourceLinkInterface::PRIORITY => 1,
];

$link = $stockSourceLinkFactory->create();
$dataObjectHelper->populateWithArray($link, $linkData, \Magento\InventoryApi\Api\Data\StockSourceLinkInterface::class);

$stockSourceLinksSave->execute([$link]);

$sourceItemData = [
    \Magento\InventoryApi\Api\Data\SourceItemInterface::SOURCE_CODE => 'test-source-code',
    \Magento\InventoryApi\Api\Data\SourceItemInterface::SKU => 'product',
    \Magento\InventoryApi\Api\Data\SourceItemInterface::QUANTITY => 100,
    \Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS => \Magento\InventoryApi\Api\Data\SourceItemInterface::STATUS_IN_STOCK,
];

$dataObjectHelper = $objectManager->get(\Magento\Framework\Api\DataObjectHelper::class);
$sourceItemFactory = $objectManager->get(\Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory::class);
$sourceItemsSave = $objectManager->get(\Magento\InventoryApi\Api\SourceItemsSaveInterface::class);

$sourceItems = [];
$sourceItem = $sourceItemFactory->create();
$dataObjectHelper->populateWithArray($sourceItem, $sourceItemData, \Magento\InventoryApi\Api\Data\SourceItemInterface::class);
$sourceItems[] = $sourceItem;
$sourceItemsSave->execute($sourceItems);
