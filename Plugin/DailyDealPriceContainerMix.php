<?php

namespace MageSuite\ProductTile\Plugin;

class DailyDealPriceContainerMix
{
    /**
     * @var \MageSuite\DailyDeal\Helper\OfferData
     */
    protected $offerDataHelper;

    public function __construct(\MageSuite\DailyDeal\Helper\OfferData $offerDataHelper)
    {
        $this->offerDataHelper = $offerDataHelper;
    }

    public function aroundGetData(\MageSuite\ProductTile\Block\Tile\Container $subject, callable $proceed, $key, $index = '') {

        $nameInLayout = $subject->getNameInLayout();

        $result = $proceed($key, $index);

        if($nameInLayout == 'product.tile.price.wrapper' and $key = 'css_class') {
            $dailyDealData = $this->offerDataHelper->prepareOfferData($subject->getProduct());

            if($dailyDealData && $dailyDealData['deal'] && $dailyDealData['displayType'] === 'badge_counter') {
                $result .= ' cs-grid-product__price--dailydeal-countdown';
            }
        }

        return $result;
    }
}