<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model of the Insights response.
 *
 * @property-read \MaxMind\MinFraud\Model\BillingAddress $billingAddress An object
 * containing minFraud data related to the billing address used in the
 * transaction.
 * @property-read \MaxMind\MinFraud\Model\CreditCard $creditCard An object containing
 * minFraud data about the credit card used in the transaction.
 * @property-read \MaxMind\MinFraud\Model\Device $device This object contains
 * information about the device that MaxMind believes is associated with the
 * IP address passed in the request.
 * @property-read \MaxMind\MinFraud\Model\Email $email This object contains
 * information about the email address passed in the request.
 * @property-read \MaxMind\MinFraud\Model\IpAddress $ipAddress An object containing
 * GeoIP2 and minFraud Insights information about the geolocated IP address.
 * @property-read \MaxMind\MinFraud\Model\ShippingAddress $shippingAddress An object
 * containing minFraud data related to the shipping address used in the
 * transaction.
 */
class Insights extends Score
{
    /**
     * @internal
     *
     * @var BillingAddress
     */
    protected $billingAddress;

    /**
     * @internal
     *
     * @var CreditCard
     */
    protected $creditCard;

    /**
     * @internal
     *
     * @var Device
     */
    protected $device;

    /**
     * @internal
     *
     * @var Email
     */
    protected $email;

    /**
     * @internal
     *
     * @var IpAddress
     */
    protected $ipAddress;

    /**
     * @internal
     *
     * @var ShippingAddress
     */
    protected $shippingAddress;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->billingAddress
            = new BillingAddress($this->safeArrayLookup($response['billing_address']));
        $this->creditCard
            = new CreditCard($this->safeArrayLookup($response['credit_card']));
        $this->device
            = new Device($this->safeArrayLookup($response['device']));
        $this->email
            = new Email($this->safeArrayLookup($response['email']));
        $this->ipAddress
            = new IpAddress($this->safeArrayLookup($response['ip_address']), $locales);
        $this->shippingAddress
            = new ShippingAddress($this->safeArrayLookup($response['shipping_address']));
    }
}
