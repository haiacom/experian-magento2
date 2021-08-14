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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\AllowedCountries;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;

/**
 * Class Config.
 */
class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var AllowedCountries
     */
    private $allowedCountries;

    /**
     * @var RegionCollectionFactory
     */
    private $regionCollectionFactory;

    private $regionMappings = [];

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        ScopeConfigInterface $scopeConfigInterface,
        Json $json,
        DirectoryHelper $directoryHelper,
        AllowedCountries $allowedCountries,
        RegionCollectionFactory $regionCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfigInterface;
        $this->json = $json;
        $this->directoryHelper = $directoryHelper;
        $this->allowedCountries = $allowedCountries;
        $this->regionCollectionFactory = $regionCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($path, $store = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @param $store
     * @return array
     */
    public function getSettings($store = null): array
    {
        $currSettings = [
            'elementPatterns' => $this->getFieldPatterns(self::XML_PATH_FIELD_PATTERN, $store),
            'elementPatternsShippingCheckout' => $this->getFieldPatterns(self::XML_PATH_FIELD_PATTERN_SHIPPING_CHECKOUT, $store),
            'elementPatternsBillingCheckout' => $this->getFieldPatterns(self::XML_PATH_FIELD_PATTERN_BILLING_CHECKOUT, $store),
            'token' => $this->getValue(self::XML_PATH_KEY),
            'currentCountryCode' => $this->directoryHelper->getDefaultCountry($store),
            'endpoint' => $this->getValue(self::XML_PATH_URL),
            'endpointFormat' => $this->getValue(self::XML_PATH_URL_FORMAT),
            'timeout' => $this->getValue(self::XML_PATH_TIMEOUT),
            'minSearchLength' => $this->getValue(self::XML_PATH_MIN_LENGTH),
            'existingRegions' => $this->getExistingRegions(),
            'datasetMappings' => $this->getCountryDatasetMappings($store)
        ];
        return array_merge(self::DEFAULT_SETTINGS, $currSettings);
    }

    private function getExistingRegions()
    {
        if (empty($this->regionMappings)) {
            $allowedCountries = $this->allowedCountries->getAllowedCountries();
            $regionCollection = $this->regionCollectionFactory->create()
                ->addFieldToFilter('country_id', $allowedCountries);
            foreach ($regionCollection as $item) {
                $this->regionMappings[$item->getData('country_id')][$item->getData('code')] = $item->getData('region_id');
            }
        }
        return $this->regionMappings;
    }

    /**
     * @param bool $all
     * @return array
     */
    public function getFieldPatterns($path, $store = null, bool $all = false): array
    {
        $result = [];
        try {
            $mappings = $this->json->unserialize($this->getValue($path, $store));
            if (is_array($mappings)) {
                if ($all) {
                    return array_values($mappings);
                }
                foreach ($mappings as $mapping) {
                    if ((int)@$mapping[self::FIELD_COLMN_ACTIVE]) {
                        $result[] = $mapping[self::FIELD_COLMN_PATTERN];
                    }
                }
            }
        }
        catch (\Exception $e) {

        }
        return $result;
    }

    /**
     * @param bool $all
     * @return array
     */
    public function getCountryDatasetMappings($store = nul): array
    {
        $result = [];
        $mappings = $this->json->unserialize($this->getValue(self::XML_PATH_COUNTRY_DATASET, $store));
        if (is_array($mappings)) {
            foreach ($mappings as $mapping) {
                $code = explode('|', $mapping[self::COUNTRY_COLMN_ISO3]);
                $result[$code[0]] = [
                    'iso3' => $code[1] ?: $code[0],
                    'dataset' => $mapping[self::COUNTRY_COLMN_DATASET]
                ];
            }
        }
        return $result;
    }
}
