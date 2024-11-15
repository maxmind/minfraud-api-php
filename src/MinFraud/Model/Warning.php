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
 */
class Warning implements \JsonSerializable
{
    /**
     * @var string this value is a machine-readable code identifying the
     *             warning
     */
    public readonly string $code;

    /**
     * @var string This property provides a human-readable
     *             explanation of the warning. The description may change at any time and
     *             should not be matched against.
     */
    public readonly string $warning;

    /**
     * @var string|null A JSON Pointer to the input field
     *                  that the warning is associated with. For instance, if the warning was about
     *                  the billing city, this would be `/billing/city`. If it was for the price in
     *                  the second shopping cart item, it would be `/shopping_cart/1/price`.
     */
    public readonly ?string $inputPointer;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        $this->code = $response['code'];
        $this->warning = $response['warning'];
        $this->inputPointer = $response['input_pointer'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        $js['code'] = $this->code;
        $js['warning'] = $this->warning;
        $js['input_pointer'] = $this->inputPointer;

        return $js;
    }
}
