<?php

namespace MageSuite\ProductTile\Cache;

class WishlistItem implements CacheKeyModel, \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment)
    {
        /** @var \Magento\Wishlist\Model\Item $wishlistItem */
        $wishlistItem = $fragment->getTile()->getWishlistItem();

        if(!$wishlistItem) {
            return [];
        }

        $wishlistId = $wishlistItem->getWishlistId();
        $wishlistItemId = $wishlistItem->getId();

        return [$wishlistId, $wishlistItemId];
    }
}
