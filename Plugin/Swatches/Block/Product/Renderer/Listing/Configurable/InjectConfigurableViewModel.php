<?php

namespace MageSuite\ProductTile\Plugin\Swatches\Block\Product\Renderer\Listing\Configurable;

/**
 * In Magento 2.3.4 there is new ViewModel for Magento\Swatches\Block\Product\Renderer\Listing\Configurable block
 * To avoid creating separate backward incompatible version of product-tile module we are injecting it programatically
 * Doing direct injection in XML would be impossible since for earlier versions class would not be found
 */
class InjectConfigurableViewModel
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function aroundGetData($subject, callable $proceed, $key) {
        if($key !== 'configurable_view_model') {
            return $proceed($key);
        }

        if(class_exists('Magento\Swatches\ViewModel\Product\Renderer\Configurable')) {
            return $this->objectManager->get('Magento\Swatches\ViewModel\Product\Renderer\Configurable');
        }

        return null;
    }
}
