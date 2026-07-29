<?php

namespace MageSuite\ProductTile\Cache;

class WishlistItem implements CacheKeyModel, \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @param \MageSuite\ProductTile\Block\Tile\Fragment $fragment
     * @return string[]
     */
    public function getCacheKeyInfo(\MageSuite\ProductTile\Block\Tile\Fragment $fragment) //phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
    {
        /** @var \Magento\Wishlist\Model\Item $wishlistItem */
        $wishlistItem = $fragment->getTile()->getWishlistItem();

        if (!$wishlistItem) {
            return [];
        }

        return [
            $wishlistItem->getWishlistId(),
            $wishlistItem->getId(),
            sprintf('%.4F', (float)$wishlistItem->getQty()),
            $this->getBuyRequestHash($wishlistItem),
            (string)$wishlistItem->getDescription()
        ];
    }

    protected function getBuyRequestHash(\Magento\Wishlist\Model\Item $wishlistItem): string
    {
        $option = $wishlistItem->getOptionByCode('info_buyRequest');

        if (!$option || !$option->getValue()) {
            return '';
        }

        return hash('md5', (string)$option->getValue());
    }
}
