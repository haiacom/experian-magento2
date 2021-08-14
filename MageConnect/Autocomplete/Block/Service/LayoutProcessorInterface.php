<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block\Service;

interface LayoutProcessorInterface
{

    /**
     * Process js Layout of block.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout);
}
