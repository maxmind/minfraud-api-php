<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Warning about the minFraud request.
 *
 * Although more codes may be added in the future, the current warning codes
 * are:
 *
 * * `BILLING_CITY_NOT_FOUND` - the billing city could not be found in our
 *   database.
 * * `BILLING_COUNTRY_MISSING` - billing address information was provided
 *   without providing a billing country.
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
 * * `SHIPPING_COUNTRY_MISSING` - shipping address information was provided
 *   without providing a shipping country.
 * * `SHIPPING_COUNTRY_NOT_FOUND` - the shipping country could not be found in
 *   our database.
 * * `SHIPPING_POSTAL_NOT_FOUND` - the shipping postal could not be found in
 *   our database.
 *
 * @property-read string $code This value is a machine-readable code identifying the
 * warning.
 * @property-read string $warning This property provides a human-readable
 * explanation of the warning. The description may change at any time and
 * should not be matched against.
 * @property-read array|null $inputPointer A JSON Pointer to the input field
 * that the warning is associated with. For instance, if the warning was about
 * the billing city, this would be `/billing/city`. If it was for the price in
 * the second shopping cart item, it would be `/shopping_cart/1/price`.
 */
class Warning extends AbstractModel
{
    /**
     * @internal
     *
     * @var string
     */
    protected $code;

    /**
     * @internal
     *
     * @var string
     */
    protected $warning;

    /**
     * @internal
     *
     * @var string
     */
    protected $inputPointer;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->code = $this->safeArrayLookup($response['code']);
        $this->warning = $this->safeArrayLookup($response['warning']);
        $this->inputPointer = $this->safeArrayLookup($response['input_pointer']);
    }
}
