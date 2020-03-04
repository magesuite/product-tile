<?php

namespace MageSuite\ProductTile\Service;

class CacheKeyPrefixGenerator implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    const CACHE_KEY_PREFIX = 'TILE_%s_%s_%s';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
    }

    public function generate()
    {
        $store = $this->storeManager->getStore();

        return sprintf(
            self::CACHE_KEY_PREFIX,
            $store->getId(),
            $this->customerSession->getCustomerGroupId(),
            $store->getCurrentCurrency()->getCode()
        );
    }
}