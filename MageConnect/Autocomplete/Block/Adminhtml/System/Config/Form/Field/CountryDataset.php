<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field;

use MageConnect\Autocomplete\Api\ConfigInterface as ConfigProvider;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class CountryDataset
 * @package MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field
 */
class CountryDataset extends AbstractFieldArray
{
    protected $countryRenderer;

    /**
     * @return mixed
     */
    protected function countryRenderer()
    {
        if (!$this->countryRenderer) {
            $this->countryRenderer = $this->getLayout()
                ->createBlock(
                    '\MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field\Columns\Country',
                    'countryRenderer',
                    ['data' => ['is_render_to_js_template' => true]]
                );
        }
        return $this->countryRenderer;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            ConfigProvider::COUNTRY_COLMN_ISO3,
            [
                'label' => __('Country'),
                'renderer' => $this->countryRenderer(),
                'style' => '100px'
            ]
        );

        $this->addColumn(
            ConfigProvider::COUNTRY_COLMN_DATASET,
            [
                'label' => __('Dataset'),
                'style' => '100px'
            ]
        );
        $this->_addButtonLabel = __('Add Field');
    }

    /**
     * @param \Magento\Framework\DataObject $row
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];
        if ($iso3 = $row->getData(ConfigProvider::COUNTRY_COLMN_ISO3)) {
            $options['option_' . $this->countryRenderer()->calcOptionHash($iso3)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
