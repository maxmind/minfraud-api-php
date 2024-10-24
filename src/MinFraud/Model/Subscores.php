<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * The scores for risk factors that are used in calculating the riskScore.
 *
 * @deprecated use RiskScoreReason instead
 */
class Subscores implements \JsonSerializable
{
    /**
     * @var float|null The risk associated with the AVS result. If
     *                 present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $avsResult;

    /**
     * @var float|null The risk associated with the billing
     *                 address. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $billingAddress;

    /**
     * @var float|null The risk
     *                 associated with the distance between the billing address and the
     *                 location for the given IP address. If present, this is a value in the
     *                 range 0.01 to 99.
     */
    public readonly ?float $billingAddressDistanceToIpLocation;

    /**
     * @var float|null The risk associated with the browser
     *                 attributes such as the User-Agent and Accept-Language. If present, this is
     *                 a value in the range 0.01 to 99.
     */
    public readonly ?float $browser;

    /**
     * @var float|null Individualized risk of chargeback for the
     *                 given IP address given for your account and any shop ID passed. This is
     *                 only available to users sending chargeback data to MaxMind. If present,
     *                 this is a value in the range 0.01 to 99.
     */
    public readonly ?float $chargeback;

    /**
     * @var float|null The risk associated with the country the
     *                 transaction originated from. If present, this is a value in the range 0.01
     *                 to 99.
     */
    public readonly ?float $country;

    /**
     * @var float|null The risk associated with the
     *                 combination of IP country, card issuer country, billing country, and
     *                 shipping country. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $countryMismatch;

    /**
     * @var float|null The risk associated with the CVV result. If
     *                 present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $cvvResult;

    /**
     * @var float|null The risk associated with the device. If
     *                 present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $device;

    /**
     * @var float|null The risk associated with the particular
     *                 email address. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $emailAddress;

    /**
     * @var float|null The general risk associated with the
     *                 email domain. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $emailDomain;

    /**
     * @var float|null The risk associated with the email
     *                 address local part (the part of the email address before the @ symbol). If
     *                 present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $emailLocalPart;

    /**
     * @var float|null The risk associated with the
     *                 particular issuer ID number (IIN) given the billing location and the
     *                 history of usage of the IIN on your account and shop ID. If present, this
     *                 is a value in the range 0.01 to 99.
     */
    public readonly ?float $issuerIdNumber;

    /**
     * @var float|null The risk associated with the particular
     *                 order amount for your account and shop ID. If present, this is a value in
     *                 the range 0.01 to 99.
     */
    public readonly ?float $orderAmount;

    /**
     * @var float|null The risk associated with the particular
     *                 phone number. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $phoneNumber;

    /**
     * @var float|null The risk associated with the
     *                 shipping address. If present, this is a value in the range 0.01 to 99.
     */
    public readonly ?float $shippingAddress;

    /**
     * @var float|null The risk
     *                 associated with the distance between the shipping address and the IP
     *                 location for the given IP address. If present, this is a value in the
     *                 range 0.01 to 99.
     */
    public readonly ?float $shippingAddressDistanceToIpLocation;

    /**
     * @var float|null The risk associated with the local time of
     *                 day of the transaction in the IP address location. If present, this is a
     *                 value in the range 0.01 to 99.
     */
    public readonly ?float $timeOfDay;

    public function __construct(?array $response)
    {
        $this->avsResult = $response['avs_result'] ?? null;
        $this->billingAddress = $response['billing_address'] ?? null;
        $this->billingAddressDistanceToIpLocation
            = $response['billing_address_distance_to_ip_location'] ?? null;
        $this->browser = $response['browser'] ?? null;
        $this->chargeback = $response['chargeback'] ?? null;
        $this->country = $response['country'] ?? null;
        $this->countryMismatch = $response['country_mismatch'] ?? null;
        $this->cvvResult = $response['cvv_result'] ?? null;
        $this->device = $response['device'] ?? null;
        $this->emailAddress = $response['email_address'] ?? null;
        $this->emailDomain = $response['email_domain'] ?? null;
        $this->emailLocalPart = $response['email_local_part'] ?? null;
        $this->issuerIdNumber = $response['issuer_id_number'] ?? null;
        $this->orderAmount = $response['order_amount'] ?? null;
        $this->phoneNumber = $response['phone_number'] ?? null;
        $this->shippingAddress = $response['shipping_address'] ?? null;
        $this->shippingAddressDistanceToIpLocation
            = $response['shipping_address_distance_to_ip_location'] ?? null;
        $this->timeOfDay = $response['time_of_day'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->avsResult !== null) {
            $js['avs_result'] = $this->avsResult;
        }
        if ($this->billingAddress !== null) {
            $js['billing_address'] = $this->billingAddress;
        }
        if ($this->billingAddressDistanceToIpLocation !== null) {
            $js['billing_address_distance_to_ip_location'] = $this->billingAddressDistanceToIpLocation;
        }
        if ($this->browser !== null) {
            $js['browser'] = $this->browser;
        }
        if ($this->chargeback !== null) {
            $js['chargeback'] = $this->chargeback;
        }
        if ($this->country !== null) {
            $js['country'] = $this->country;
        }
        if ($this->countryMismatch !== null) {
            $js['country_mismatch'] = $this->countryMismatch;
        }
        if ($this->cvvResult !== null) {
            $js['cvv_result'] = $this->cvvResult;
        }
        if ($this->device !== null) {
            $js['device'] = $this->device;
        }
        if ($this->emailAddress !== null) {
            $js['email_address'] = $this->emailAddress;
        }
        if ($this->emailDomain !== null) {
            $js['email_domain'] = $this->emailDomain;
        }
        if ($this->emailLocalPart !== null) {
            $js['email_local_part'] = $this->emailLocalPart;
        }
        if ($this->issuerIdNumber !== null) {
            $js['issuer_id_number'] = $this->issuerIdNumber;
        }
        if ($this->orderAmount !== null) {
            $js['order_amount'] = $this->orderAmount;
        }
        if ($this->phoneNumber !== null) {
            $js['phone_number'] = $this->phoneNumber;
        }
        if ($this->shippingAddress !== null) {
            $js['shipping_address'] = $this->shippingAddress;
        }
        if ($this->shippingAddressDistanceToIpLocation !== null) {
            $js['shipping_address_distance_to_ip_location'] = $this->shippingAddressDistanceToIpLocation;
        }
        if ($this->timeOfDay !== null) {
            $js['time_of_day'] = $this->timeOfDay;
        }

        return $js;
    }
}
