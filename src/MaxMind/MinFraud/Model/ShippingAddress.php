<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class ShippingAddress
 * @package MaxMind\MinFraud\Model
 */
class ShippingAddress extends Address
{
    protected $isHighRisk;
    protected $distanceToBillingAddress;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        parent::__construct($response, $locales);
        $this->isHighRisk = $this->get($response['is_high_risk']);
        $this->distanceToBillingAddress
            = $this->get($response['distance_to_billing_address']);
    }
}
