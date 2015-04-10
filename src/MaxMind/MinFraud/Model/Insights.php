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
    protected $warnings;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        parent::__construct($response, $locales);
        $this->ipLocation
            = new IpLocation($this->get($response['ip_location']));
        $this->creditCard
            = new CreditCard($this->get($response['credit_card']));
        $this->shippingAddress
            = new ShippingAddress($this->get($response['shipping_address']));
        $this->billingAddress
            = new BillingAddress($this->get($response['billing_address']));
    }

    /**
     * @return IpLocation
     */
    public function ipLocation()
    {
        return $this->ipLocation;
    }

    /**
     * @return CreditCard
     */
    public function creditCard()
    {
        return $this->creditCard;
    }

    /**
     * @return ShippingAddress
     */
    public function shippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @return BillingAddress
     */
    public function billingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @return array
     */
    public function warnings()
    {
        return $this->warnings;
    }
}
