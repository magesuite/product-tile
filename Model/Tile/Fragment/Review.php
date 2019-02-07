<?php

namespace MageSuite\ProductTile\Model\Tile\Fragment;

class Review implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Review\Model\Review
     */
    protected $review;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Review\Model\Review $review,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->review = $review;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return float
     */
    public function getActiveStars(\Magento\Catalog\Model\Product $product)
    {
        $ratingSummary = $this->getRatingSummary($product);

        $activeStars = $ratingSummary->getRatingSummary() ? (round($ratingSummary->getRatingSummary() / 10) / 2) : 0;

        return $activeStars;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     */
    public function getReviewsCount(\Magento\Catalog\Model\Product $product)
    {
        $ratingSummary = $this->getRatingSummary($product);

        return $ratingSummary->getReviewsCount();
    }

    protected function getRatingSummary(\Magento\Catalog\Model\Product $product)
    {
        $storeId = $this->storeManager->getStore()->getId();

        $ratingSummary = $product->getRatingSummary();

        if (!$ratingSummary) {
            $this->review->getEntitySummary($product, $storeId);
        }

        return $product->getRatingSummary();
    }
}