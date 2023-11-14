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
        // Since 2.3.3 rating summary is being returned directly, not as an object.
        if (is_object($ratingSummary)) {
            $ratingSummary = $ratingSummary->getRatingSummary() ?? 0;
        }

        $activeStars = round($ratingSummary / 10) / 2;

        return $activeStars;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     */
    public function getReviewsCount(\Magento\Catalog\Model\Product $product)
    {
        $ratingSummary = $this->getRatingSummary($product);
        // Since 2.3.3 rating summary is being returned directly, not as an object.
        if (is_object($ratingSummary)) {
            return $ratingSummary->getReviewsCount();
        }

        return $product->getReviewsCount();
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
