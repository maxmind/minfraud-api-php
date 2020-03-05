<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model containing properties of the shipping address.
 *
 * @property-read int|null $distanceToBillingAddress The distance in kilometers
 * from the shipping address to billing address.
 * @property-read bool|null $isHighRisk This field is true if the shipping
 * address is an address associated with fraudulent transactions. The field is
 * false when the address is not associated with increased risk. The key will
 * only be present when a shipping address is provided.
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
