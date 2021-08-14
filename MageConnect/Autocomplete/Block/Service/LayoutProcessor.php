<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block\Service;

use MageConnect\Autocomplete\Api\ConfigInterface;

/**
 * Class LayoutProcessor.
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * LayoutProcessor constructor.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Process js Layout of block.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        return  $this->addSettings($jsLayout);
    }

    /**
     * @param $jsLayout
     *
     * @return mixed
     */
    public function addSettings($layout)
    {
        $settings = [];
        if (isset($layout['components']['experian']['settings'])) {
            $settings = $layout['components']['experian']['settings'];
        }
        $layout['components']['experian']['settings'] = array_replace_recursive(
            $this->config->getSettings(),
            $settings
        );
        return $layout;
    }
}
