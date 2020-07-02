<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$stockRepository = $objectManager->get(\Magento\InventoryApi\Api\StockRepositoryInterface::class);
$connection = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);

$connection->getConnection()->delete(
    $connection->getTableName('inventory_source'),
    [
        \Magento\InventoryApi\Api\Data\SourceInterface::SOURCE_CODE . ' = ?' => 'test-source-code',
    ]
);

try {
    $stockRepository->deleteById(10);
} catch (\Exception $e) {

}
