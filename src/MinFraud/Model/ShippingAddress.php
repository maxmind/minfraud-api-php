<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model containing properties of the shipping address.
 *
 * @property-read int|null $distanceToBillingAddress The distance in kilometers
 * from the shipping address to billing address.
 * @property-read bool|null $isHighRisk This property is true if the shipping
 * address is in the IP country. The property is false when the address is not
 * in the IP country. If the shipping address could not be parsed or was not
 * provided or the IP address could not be geolocated, then the property is
 * null.
 */
class ShippingAddress extends Address
{
    /**
     * @internal
     */
    protected $isHighRisk;

    /**
     * @internal
     */
    protected $distanceToBillingAddress;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->isHighRisk = $this->safeArrayLookup($response['is_high_risk']);
        $this->distanceToBillingAddress
            = $this->safeArrayLookup($response['distance_to_billing_address']);
    }
}
