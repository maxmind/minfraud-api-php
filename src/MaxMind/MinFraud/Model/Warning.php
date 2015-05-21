<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Warning
 * @package MaxMind\MinFraud\Model
 *
 * @property string $code This value is a machine-readable code identifying the
 * warning. Although more codes may be added in the future, the current codes
 * are:
 *
 * * `BILLING_CITY_NOT_FOUND` - the billing city could not be found in our
 *   database.
 * * `BILLING_COUNTRY_NOT_FOUND` - the billing country could not be found in
 *   our database.
 * * `BILLING_POSTAL_NOT_FOUND` - the billing postal could not be found in our
 *   database.
 * * `INPUT_INVALID` - the value associated with the key does not meet the
 *   required constraints, e.g., "United States" in a field that requires a
 *   two-letter country code.
 * * `INPUT_UNKNOWN` - an unknown key was encountered in the request body.
 * * `IP_ADDRESS_NOT_FOUND` - the IP address could not be geolocated.
 * * `SHIPPING_CITY_NOT_FOUND` - the shipping city could not be found in our
 *   database.
 * * `SHIPPING_COUNTRY_NOT_FOUND` - the shipping country could not be found in
 *   our database.
 * * `SHIPPING_POSTAL_NOT_FOUND` - the shipping postal could not be found in
 *   our database.
 *
 * @property string $warning This property provides a human-readable
 * explanation of the warning. The description may change at any time and
 * should not be matched against.
 * @property array $input This is an array of keys representing the path to the
 * input that the warning is associated with. For instance, if the warning was
 * about the billing city, the array would be `["billing", "city"]`. The key is
 * used for an object and the index number for an array.
 */
class Warning extends AbstractModel
{
    /**
     * @internal
     */
    protected $code;

    /**
     * @internal
     */
    protected $warning;

    /**
     * @internal
     */
    protected $input;

    public function __construct($response, $locales = array('en'))
    {
        $this->code = $this->safeArrayLookup($response['code']);
        $this->warning = $this->safeArrayLookup($response['warning']);
        $this->input = $this->safeArrayLookup($response['input']);
    }
}
