<?php
/**
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field\Columns;

use MageConnect\Autocomplete\Model\Config\Source\Country as CountrySource;

/**
 * Class Country
 * @package MageConnect\Autocomplete\Block\Adminhtml\System\Config\Form\Field\Columns
 */
class Country extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var CountrySource
     */
    protected $countrySource;

    /**
     * Attributes constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Betta\Catalog\Model\Config\Source\FilterableAttributes $attributes
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        CountrySource $countrySource,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->countrySource = $countrySource;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->countrySource->toOptionArray());
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
