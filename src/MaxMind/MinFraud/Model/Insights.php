<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Insights
 * @package MaxMind\MinFraud\Model
 */
class Insights extends Score
{
    protected $ipLocation;
    protected $creditCard;
    protected $shippingAddress;
    protected $billingAddress;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        parent::__construct($response, $locales);
        $this->ipLocation
            = new IpLocation($this->get($response['ip_location']), $locales);
        $this->creditCard
            = new CreditCard($this->get($response['credit_card']));
        $this->shippingAddress
            = new ShippingAddress($this->get($response['shipping_address']));
        $this->billingAddress
            = new BillingAddress($this->get($response['billing_address']));
    }
}
