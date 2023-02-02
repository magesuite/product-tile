<?php

namespace MageSuite\ProductTile\Model\Tile\Fragment;

class Review implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \Magento\Review\Model\Review $review;

    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected \MageSuite\Frontend\Helper\Product $productHelper;

    protected array $reviewSummary = [];

    public function __construct(
        \Magento\Review\Model\Review $review,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Frontend\Helper\Product $productHelper
    ) {
        $this->review = $review;
        $this->storeManager = $storeManager;
        $this->productHelper = $productHelper;
    }

    public function getActiveStars(\Magento\Catalog\Model\Product $product)
    {
        $reviewSummary = $this->getReviewSummary($product);

        return $reviewSummary['data']['activeStars'];
    }

    public function getReviewsCount(\Magento\Catalog\Model\Product $product)
    {
        $reviewSummary = $this->getReviewSummary($product);
        $votes = $reviewSummary['data']['votes'];

        return array_sum($votes);
    }

    protected function getReviewSummary($product): array
    {
        $productId = $product->getId();
        
        if (!isset($this->reviewSummary[$productId])) {
            $this->reviewSummary[$productId] = $this->productHelper->getReviewSummary($product, true);
        }

        return $this->reviewSummary[$productId];
    }
}