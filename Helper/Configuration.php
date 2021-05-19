<?php

declare(strict_types=1);

namespace MageSuite\ProductTile\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_THUMBNAIL_GALLERY_ENABLED = 'product_tile/thumbnail_gallery/enabled';
    const XML_PATH_INCLUDE_CUSTOMER_GROUP_IN_CACHE_KEY = 'product_tile/cache/include_customer_group_in_cache_key';

    public function isThumbnailGalleryEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_THUMBNAIL_GALLERY_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function includeCustomerGroupInCacheKey($storeId = null) : int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_INCLUDE_CUSTOMER_GROUP_IN_CACHE_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
