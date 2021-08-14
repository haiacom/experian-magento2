<?php

/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block;

use Magento\Framework\Serialize\Serializer\Json as SerializerJson;
use Magento\Framework\View\Element\Template;

class Service extends Template
{
    /**
     * @var array
     */
    private $layoutProcessors;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * Checker constructor.
     * @param Template\Context $context
     * @param SerializerJson $serializer
     * @param array $data
     * @param array $layoutProcessors
     */
    public function __construct(
        Template\Context $context,
        SerializerJson $serializer,
        array $data = [],
        array $layoutProcessors = []
    ) {
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->layoutProcessors = $layoutProcessors;
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }

        return $this->serializer->serialize($this->jsLayout);
    }

}
