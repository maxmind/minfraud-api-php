<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class ShippingAddress
 * @package MaxMind\MinFraud\Model
 *
 * This model contains properties of the shipping address.
 *
 * @property integer $distanceToBillingAddress The distance in kilometers from
 * the shipping address to billing address.
 * @property boolean $isHighRisk This property is true if the shipping address
 * is in the IP country. The property is false when the address is not in the
 * IP country. If the shipping address could not be parsed or was not provided
 * or the IP address could not be geo-located, then the property is null.
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

    public function __construct($response, $locales = array('en'))
    {
        parent::__construct($response, $locales);
        $this->isHighRisk = $this->safeArrayLookup($response['is_high_risk']);
        $this->distanceToBillingAddress
            = $this->safeArrayLookup($response['distance_to_billing_address']);
    }
}
