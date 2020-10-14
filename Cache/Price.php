<?php

namespace MageSuite\ProductTile\Cache;

class Price implements CacheKeyModel, \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \MageSuite\ProductTile\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \MageSuite\ProductTile\Helper\Configuration $configuration
    )
    {
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->configuration = $configuration;
    }

    /**
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment)
    {
        $cacheKey = [
            $fragment->getProduct()->getSpecialPrice(),
            $this->storeManager->getStore()->getId(),
            $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
            $fragment->getTile()->getViewMode()
        ];

        if($this->configuration->includeCustomerGroupInCacheKey()) {
            $cacheKey[] = $this->customerSession->getCustomerGroupId();
        }

        return $cacheKey;
    }
}
