<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * The scores for risk factors that are used in calculating the riskScore.
 *
 * @property-read float|null $avsResult The risk associated with the AVS result. If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null $billingAddress The risk associated with the billing
 * address. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $billingAddressDistanceToIpLocation The risk
 * associated with the distance between the billing address and the
 * location for the given IP address. If present, this is a value in the
 * range 0.01 to 99.
 * @property-read float|null $browser The risk associated with the browser
 * attributes such as the User-Agent and Accept-Language. If present, this is
 * a value in the range 0.01 to 99.
 * @property-read float|null $chargeback Individualized risk of chargeback for the
 * given IP address given for your account and any shop ID passed. This is
 * only available to users sending chargeback data to MaxMind. If present,
 * this is a value in the range 0.01 to 99.
 * @property-read float|null $country The risk associated with the country the
 * transaction originated from. If present, this is a value in the range 0.01
 * to 99.
 * @property-read float|null $countryMismatch The risk associated with the
 * combination of IP country, card issuer country, billing country, and
 * shipping country. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $cvvResult The risk associated with the CVV result. If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null $device The risk associated with the device. If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null $emailAddress The risk associated with the particular
 * email address. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $emailDomain The general risk associated with the
 * email domain. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $emailLocalPart The risk associated with the email
 * address local part (the part of the email address before the @ symbol). If
 * present, this is a value in the range 0.01 to 99.
 * @property-read float|null $emailTenure The risk associated with the issuer ID
 * number on the email domain. If present, this is a value in the range 0.01
 * to 99. <b>Deprecated effective August 29, 2019. This risk factor score will
 * default to 1 and will be removed in a future release. The user tenure on
 * email is reflected in the `/subscores/email_address` output.</b>
 * @property-read float|null $ipTenure The risk associated with the issuer ID
 * number on the IP address. If present, this is a value in the range 0.01 to
 * 99. <b>Deprecated effective August 29, 2019. This risk factor score will
 * default to 1 and will be removed in a future release. The IP tenure is
 * reflected in the overall risk score.</b>
 * @property-read float|null $issuerIdNumber The risk associated with the
 * particular issuer ID number (IIN) given the billing location and the
 * history of usage of the IIN on your account and shop ID. If present, this
 * is a value in the range 0.01 to 99.
 * @property-read float|null $orderAmount The risk associated with the particular
 * order amount for your account and shop ID. If present, this is a value in
 * the range 0.01 to 99.
 * @property-read float|null $phoneNumber The risk associated with the particular
 * phone number. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $shippingAddress The risk associated with the
 * shipping address. If present, this is a value in the range 0.01 to 99.
 * @property-read float|null $shippingAddressDistanceToIpLocation The risk
 * associated with the distance between the shipping address and the IP
 * location for the given IP address. If present, this is a value in the
 * range 0.01 to 99.
 * @property-read float|null $timeOfDay The risk associated with the local time of
 * day of the transaction in the IP address location. If present, this is a
 * value in the range 0.01 to 99.
 */
class Subscores extends AbstractModel
{
    /**
     * @internal
     *
     * @var float|null
     */
    protected $avsResult;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $billingAddress;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $billingAddressDistanceToIpLocation;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $browser;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $chargeback;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $country;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $countryMismatch;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $cvvResult;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $device;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $emailAddress;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $emailDomain;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $emailLocalPart;

    /**
     * @internal
     *
     * @var float|null
     *
     * @deprecated This risk factor score will default to 1 and will be
     * removed in a future release. The user tenure on email is reflected in
     * the `/subscores/email_address` output.
     */
    protected $emailTenure;

    /**
     * @internal
     *
     * @var float|null
     *
     * @deprecated This risk factor score will default to 1 and will be
     * removed in a future release. The IP tenure is reflected in the overall
     * risk score.
     */
    protected $ipTenure;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $issuerIdNumber;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $orderAmount;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $phoneNumber;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $shippingAddress;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $shippingAddressDistanceToIpLocation;

    /**
     * @internal
     *
     * @var float|null
     */
    protected $timeOfDay;

    public function __construct(?array $response, array $locales = ['en'])
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
        $this->device = $this->safeArrayLookup($response['device']);
        $this->emailAddress = $this->safeArrayLookup($response['email_address']);
        $this->emailDomain = $this->safeArrayLookup($response['email_domain']);
        $this->emailLocalPart = $this->safeArrayLookup($response['email_local_part']);
        $this->emailTenure = $this->safeArrayLookup($response['email_tenure']);
        $this->ipTenure = $this->safeArrayLookup($response['ip_tenure']);
        $this->issuerIdNumber = $this->safeArrayLookup($response['issuer_id_number']);
        $this->orderAmount = $this->safeArrayLookup($response['order_amount']);
        $this->phoneNumber = $this->safeArrayLookup($response['phone_number']);
        $this->shippingAddress = $this->safeArrayLookup($response['shipping_address']);
        $this->shippingAddressDistanceToIpLocation
            = $this->safeArrayLookup($response['shipping_address_distance_to_ip_location']);
        $this->timeOfDay = $this->safeArrayLookup($response['time_of_day']);
    }
}
