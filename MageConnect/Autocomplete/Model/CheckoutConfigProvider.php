<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Model;

use MageConnect\Autocomplete\Api\ConfigInterface;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class CheckoutConfigProvider
 * @package MageConnect\Autocomplete\Model
 */
class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var ConfigInterface
     */
    private $experianConfig;

    /**
     * CheckoutConfigProvider constructor.
     * @param ConfigInterface $experianConfig
     */
    public function __construct(
        ConfigInterface $experianConfig
    ) {
        $this->experianConfig = $experianConfig;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'experianSettings' => $this->experianConfig->getSettings()
        ];
    }
}
