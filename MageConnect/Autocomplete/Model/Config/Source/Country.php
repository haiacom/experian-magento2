<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Model\Config\Source;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory;
use Magento\Config\Model\Config\Source\Locale\Country as LocaleCountry;
/**
 * Class Country
 * @package MageConnect\Autocomplete\Model
 */
class Country implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $countryCollection;

    /**
     * @var LocaleCountry
     */
    protected $localeCountry;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Country constructor.
     * @param CollectionFactory $countryCollection
     * @param LocaleCountry $localeCountry
     */
    public function __construct(
        CollectionFactory $countryCollection,
        LocaleCountry $localeCountry
    )
    {
        $this->countryCollection = $countryCollection;
        $this->localeCountry = $localeCountry;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = $this->localeCountry->toOptionArray();
            $iso3IdList = $this->getIso3CodeArray();
            foreach ($this->options as $i => $option) {
                $code = $option['value'] . '|' . (@$iso3IdList[$option['value']] ?: $option['value']);
                $this->options[$i]['value'] = $code;
            }
        }
        return $this->options;
    }

    /**
     * @return array
     */
    private function getIso3CodeArray()
    {
        $mappings = [];
        $countries = $this->countryCollection->create();
        foreach ($countries as $country) {
            $mappings[$country->getCountryId()] = $country->getData('iso3_code');
        }
        return $mappings;
    }
}
