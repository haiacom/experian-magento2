<?php

/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use MageConnect\Autocomplete\Api\ConfigInterface;

class Checkout implements LayoutProcessorInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * Service constructor.
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $settings = $this->config->getSettings();
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['autocomplete']['settings'] = $settings;
        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
        ['payment']['children']['payments-list']['children']['before-place-order']['children']['autocomplete']['settings'] = $settings;
        return $jsLayout;
    }

}
