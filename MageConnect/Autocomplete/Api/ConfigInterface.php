<?php
/*
 *  Developed by MageConnect
 *  Do not edit or add to this file if you wish to upgrade This to newer
 *  versions in the future.
 *  @author      minhhaislvl@gmail.com
 *
 */

namespace MageConnect\Autocomplete\Api;

/**
 * Class ConfigInterface.
 */
interface ConfigInterface
{
    /**
     * XML path config for predefined fields
     */
    const XML_PATH_ENABLE = 'experian/settings/active';
    const XML_PATH_KEY = 'experian/settings/key';
    const XML_PATH_URL = 'experian/settings/service_url';
    const XML_PATH_FORMAT_URL = 'experian/settings/format_url';
    const XML_PATH_URL_FORMAT = 'experian/settings/service_url_format';
    const XML_PATH_TIMEOUT = 'experian/settings/timeout';
    const XML_PATH_MIN_LENGTH = 'experian/settings/min_length';
    const XML_PATH_FIELD_PATTERN = 'experian/settings/field_patterns';
    const XML_PATH_FIELD_PATTERN_SHIPPING_CHECKOUT = 'experian/settings/field_patterns_shipping_checkout';
    const XML_PATH_FIELD_PATTERN_BILLING_CHECKOUT = 'experian/settings/field_patterns_billing_checkout';
    const XML_PATH_COUNTRY_DATASET = 'experian/settings/country_dataset';

    /**
     * Autocomplete fields settings
     */
    const FIELD_COLMN_NAME = 'name';
    const FIELD_COLMN_PATTERN = 'pattern';
    const FIELD_COLMN_ACTIVE = 'active';

    /**
     * Country Dataset mappings
     */
    const COUNTRY_COLMN_ISO3 = 'iso3';
    const COUNTRY_COLMN_DATASET = 'dataset';

    const DEFAULT_SETTINGS = [
        "elementPatterns" => "",
        "elementPatternsShippingCheckout" => "",
        "elementPatternsBillingCheckout" => "",
        "minSearchLength" => 1,
        "enabled" => 1,
        "token" => "",
        "timeout" => "5",
        "endpoint" => "",
        "endpointFormat" => "",
        "placeholderText" => "Start typing an address...",
        "applyFocus" => 0,
        "useSpinner" => 0,
        "elements" => [
            "country" => "country_id",
            "address_line_1" => "street[0]",
            "address_line_2" => "street[1]",
            "address_line_3" => "street[2]",
            "locality" => "city",
            "region" => "region_id",
            "postal_code" => "postcode"
        ],
        "existingRegions" => [],
        "datasetMappings" => [],
        "defaultDataset" => "DataFusion",
        "defaultCountryIso" => "AUS",
        "defaultCountryCode" => "AU"
    ];

    /**
     * @param $path
     * @param null $store
     * @return mixed
     */
    public function getValue($path, $store = null);
}
