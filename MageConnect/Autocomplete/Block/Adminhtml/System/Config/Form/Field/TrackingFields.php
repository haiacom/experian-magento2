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

class TrackingFields extends AbstractFieldArray
{
    protected $_column = null;

    /**
     * @return mixed
     */
    protected function yesNoRenderer()
    {
        if (!$this->_column) {
            $this->_column =
                $this->getLayout()
                ->createBlock(
                    '\MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field\Columns\YesNo',
                    null,
                    ['data' => ['is_render_to_js_template' => true]]
                );
        }
        return  $this->_column;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            ConfigProvider::FIELD_COLMN_NAME,
            [
                'label' => __('Name')
            ]
        );

        $this->addColumn(
            ConfigProvider::FIELD_COLMN_PATTERN,
            [
                'label' => __('Pattern')
            ]
        );
        $this->addColumn(
            ConfigProvider::FIELD_COLMN_ACTIVE,
            [
                'label' => __('Enable'),
                'renderer' => $this->yesNoRenderer()
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
        $status = $row->getData(ConfigProvider::FIELD_COLMN_ACTIVE);
        if ($status !== false) {
            $options['option_' . $this->yesNoRenderer()->calcOptionHash($status)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
