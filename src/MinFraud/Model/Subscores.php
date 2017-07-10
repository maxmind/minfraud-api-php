<?php

namespace MaxMind\MinFraud\Model;

/**
 * Subscores for components that are used in calculating the riskScore.
 *
 * @property-read float|null avsResult The risk associated with the AVS result. If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null billingAddress The risk associated with the billing
 * address. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null billingAddressDistanceToIpLocation The risk
 * associated with the distance between the billing address and the
 * location for the given IP address. If present, this is a value in the
 * range 0.01 to 99.
 * @property-read float|null browser The risk associated with the browser
 * attributes such as the User-Agent and Accept-Language. If present, this is
 * a value in the range 0.01 to 99.
 * @property-read float|null chargeback Individualized risk of chargeback for the
 * given IP address given for your account and any shop ID passed. This is
 * only available to users sending chargeback data to MaxMind. If present,
 * this is a value in the range 0.01 to 99.
 * @property-read float|null country The risk associated with the country the
 * transaction originated from. If present, this is a value in the  range 0.01
 * to 99.
 * @property-read float|null countryMismatch The risk associated with the
 * combination of IP country, card issuer country, billing country, and
 * shipping country. If present, this is a value in the  range 0.01 to 99.
 * @property-read float|null cvvResult The risk associated with the CVV result. If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null emailAddress The risk associated with the particular
 * email address. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null emailDomain The general risk associated with the
 * email domain. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null emailTenure The risk associated with the issuer ID
 * number on the email domain. If present, this is a value in the range 0.01
 * to 99.
 * @property-read float|null ipTenure The risk associated with the issuer ID
 * number on the IP address. If present, this is a value in the range 0.01 to
 * 99.
 * @property-read float|null issuerIdNumber The risk associated with the
 * particular issuer ID number (IIN) given the billing location and the
 * history of usage of the IIN on your account and shop ID. If present, this
 * is a value in the range 0.01 to 99.
 * @property-read float|null orderAmount The risk associated with the particular
 * order amount for your account and shop ID. If present, this is a value in
 * the range 0.01 to 99.
 * @property-read float|null phoneNumber The risk associated with the particular
 * phone number. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null shippingAddressDistanceToIpLocation The risk
 * associated with the distance between the shipping address and the IP
 * location for the given IP address. If present, this is a value in the
 * range 0.01 to 99.
 * @property-read float|null timeOfDay The risk associated with the local time of
 * day of the transaction in the IP address location. If present, this is a
 * value in the range 0.01 to 99.
 */
class Subscores extends AbstractModel
{
    /**
     * @internal
     */
    protected $avsResult;

    /**
     * @internal
     */
    protected $billingAddress;

    /**
     * @internal
     */
    protected $billingAddressDistanceToIpLocation;

    /**
     * @internal
     */
    protected $browser;

    /**
     * @internal
     */
    protected $chargeback;

    /**
     * @internal
     */
    protected $country;

    /**
     * @internal
     */
    protected $countryMismatch;

    /**
     * @internal
     */
    protected $cvvResult;

    /**
     * @internal
     */
    protected $emailAddress;

    /**
     * @internal
     */
    protected $emailDomain;

    /**
     * @internal
     */
    protected $emailTenure;

    /**
     * @internal
     */
    protected $ipTenure;

    /**
     * @internal
     */
    protected $issuerIdNumber;

    /**
     * @internal
     */
    protected $orderAmount;

    /**
     * @internal
     */
    protected $phoneNumber;

    /**
     * @internal
     */
    protected $shippingAddressDistanceToIpLocation;

    /**
     * @internal
     */
    protected $timeOfDay;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->avsResult = $this->safeArrayLookup($response['avs_result']);
        $this->billingAddress = $this->safeArrayLookup($response['billing_address']);
        $this->billingAddressDistanceToIpLocation
            = $this->safeArrayLookup($response['billing_address_distance_to_ip_location']);
        $this->browser = $this->safeArrayLookup($response['browser']);
        $this->chargeback = $this->safeArrayLookup($response['chargeback']);
        $this->country = $this->safeArrayLookup($response['country']);
        $this->countryMismatch = $this->safeArrayLookup($response['country_mismatch']);
        $this->cvvResult = $this->safeArrayLookup($response['cvv_result']);
        $this->emailAddress = $this->safeArrayLookup($response['email_address']);
        $this->emailDomain = $this->safeArrayLookup($response['email_domain']);
        $this->emailTenure = $this->safeArrayLookup($response['email_tenure']);
        $this->ipTenure = $this->safeArrayLookup($response['ip_tenure']);
        $this->issuerIdNumber = $this->safeArrayLookup($response['issuer_id_number']);
        $this->orderAmount = $this->safeArrayLookup($response['order_amount']);
        $this->phoneNumber = $this->safeArrayLookup($response['phone_number']);
        $this->shippingAddressDistanceToIpLocation
            = $this->safeArrayLookup($response['shipping_address_distance_to_ip_location']);
        $this->timeOfDay = $this->safeArrayLookup($response['time_of_day']);
    }
}
