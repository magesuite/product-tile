<?php

namespace MageSuite\ProductTile\Block\Tile\Fragment\Badge;

class IsNew implements \MageSuite\ProductTile\Block\Tile\Fragment\BadgeInterface
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    public function __construct(\Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate)
    {
        $this->localeDate = $localeDate;
    }

    /**
     * @return boolean
     */
    public function isVisible(\MageSuite\ProductTile\Block\Tile $tile)
    {
        $product = $tile->getProductEntity();

        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        $date = date('Y-m-d H:i:s', $this->localeDate->scopeTimeStamp());

        $fromTimestamp = strtotime($newsFromDate);
        $toTimestamp = strtotime($newsToDate);
        $dateTimestamp = strtotime($date);

        if(!$fromTimestamp && !$toTimestamp){
            return false;
        }

        if(!$fromTimestamp && $dateTimestamp <= $toTimestamp){
            return true;
        }

        if(!$toTimestamp && $dateTimestamp >= $fromTimestamp){
            return true;
        }

        if($dateTimestamp >= $fromTimestamp && $dateTimestamp <= $toTimestamp){
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getValue(\MageSuite\ProductTile\Block\Tile $tile)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getCssModifier(\MageSuite\ProductTile\Block\Tile $tile) {
        return '';
    }
}
