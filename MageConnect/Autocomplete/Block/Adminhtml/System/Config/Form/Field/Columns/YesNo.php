<?php
/**
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field\Columns;

use Magento\Config\Model\Config\Source\Yesno as SourceYesNo;

class YesNo extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var YesNo
     */
    protected $yesNo;

    /**
     * Attributes constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Betta\Catalog\Model\Config\Source\FilterableAttributes $attributes
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        SourceYesNo $yesNo,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->yesNo = $yesNo;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->yesNo->toOptionArray());
        }

        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
