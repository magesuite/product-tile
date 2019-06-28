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

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment)
    {
        return [
            $fragment->getProduct()->getSpecialPrice(),
            $this->storeManager->getStore()->getId(),
            $this->customerSession->getCustomerGroupId(),
            $fragment->getTile()->getViewMode()
        ];
    }
}