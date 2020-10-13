<?php

namespace MageSuite\ProductTile\Helper;

class Configuration
{
    const XML_PATH_PRODUCT_TILE_CACHE_CONFIGURATION = 'product_tile/cache';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $config = null;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->scopeConfig = $scopeConfigInterface;
    }

    public function includeCustomerGroupInCacheKey()
    {
        return $this->getConfig()->getIncludeCustomerGroupInCacheKey();
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $config = $this->scopeConfig->getValue(self::XML_PATH_PRODUCT_TILE_CACHE_CONFIGURATION);

            if(!is_array($config) || $config === null) {
                $config = [];
            }

            $this->config = new \Magento\Framework\DataObject($config);
        }

        return $this->config;
    }
}
