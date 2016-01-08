<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Insights
 * @package MaxMind\MinFraud\Model
 *
 * @property \MaxMind\MinFraud\Model\CreditCard $creditCard An object containing
 * minFraud data about the credit card used in the transaction.
 * @property \MaxMind\MinFraud\Model\IpAddress $ipAddress An object containing
 * GeoIP2 and minFraud Insights information about the geolocated IP address.
 * @property \MaxMind\MinFraud\Model\BillingAddress $billingAddress An object
 * containing minFraud data related to the billing address used in the
 * transaction.
 * @property \MaxMind\MinFraud\Model\ShippingAddress $shippingAddress An object
 * containing minFraud data related to the shipping address used in the
 * transaction.
 */
class Insights extends Score
{
    /**
     * @internal
     */
    protected $ipAddress;

    /**
     * @internal
     */
    protected $creditCard;

    /**
     * @internal
     */
    protected $shippingAddress;

    /**
     * @internal
     */
    protected $billingAddress;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->ipAddress
            = new IpAddress($this->safeArrayLookup($response['ip_address']), $locales);
        $this->creditCard
            = new CreditCard($this->safeArrayLookup($response['credit_card']));
        $this->shippingAddress
            = new ShippingAddress($this->safeArrayLookup($response['shipping_address']));
        $this->billingAddress
            = new BillingAddress($this->safeArrayLookup($response['billing_address']));
    }
}
