<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * The risk score reason for the multiplier.
 *
 * This class provides both a machine-readable code and a human-readable
 * explanation of the reason for the risk score, see
 * {@link https://dev.maxmind.com/minfraud/api-documentation/responses/#schema--response--risk-score-reason--multiplier-reason}.
 *
 * Although more codes may be added in the future, the current codes are:
 *
 * * `BROWSER_LANGUAGE` - Riskiness of the browser user-agent and language associated with the request.
 * * `BUSINESS_ACTIVITY` - Riskiness of business activity associated with the request.
 * * `COUNTRY` - Riskiness of the country associated with the request.
 * * `CUSTOMER_ID` - Riskiness of a customer's activity.
 * * `EMAIL_DOMAIN` - Riskiness of email domain.
 * * `EMAIL_DOMAIN_NEW` - Riskiness of newly-sighted email domain.
 * * `EMAIL_ADDRESS_NEW` - Riskiness of newly-sighted email address.
 * * `EMAIL_LOCAL_PART` - Riskiness of the local part of the email address.
 * * `EMAIL_VELOCITY` - Velocity on email - many requests on same email over short period of time.
 * * `ISSUER_ID_NUMBER_COUNTRY_MISMATCH` - Riskiness of the country mismatch between IP, billing,
 *    shipping and IIN country.
 * * `ISSUER_ID_NUMBER_ON_SHOP_ID` - Risk of Issuer ID Number for the shop ID.
 * * `ISSUER_ID_NUMBER_LAST_DIGITS_ACTIVITY` - Riskiness of many recent requests and previous
 *    high-risk requests on the IIN and last digits of the credit card.
 * * `ISSUER_ID_NUMBER_SHOP_ID_VELOCITY` - Risk of recent Issuer ID Number activity for the shop ID.
 * * `INTRACOUNTRY_DISTANCE` - Risk of distance between IP, billing, and shipping location.
 * * `ANONYMOUS_IP` - Risk due to IP being an Anonymous IP.
 * * `IP_BILLING_POSTAL_VELOCITY` - Velocity of distinct billing postal code on IP address.
 * * `IP_EMAIL_VELOCITY` - Velocity of distinct email address on IP address.
 * * `IP_HIGH_RISK_DEVICE` - High-risk device sighted on IP address.
 * * `IP_ISSUER_ID_NUMBER_VELOCITY` - Velocity of distinct IIN on IP address.
 * * `IP_ACTIVITY` - Riskiness of IP based on minFraud network activity.
 * * `LANGUAGE` - Riskiness of browser language.
 * * `MAX_RECENT_EMAIL` - Riskiness of email address based on past minFraud risk scores on email.
 * * `MAX_RECENT_PHONE` - Riskiness of phone number based on past minFraud risk scores on phone.
 * * `MAX_RECENT_SHIP` - Riskiness of email address based on past minFraud risk scores on ship address.
 * * `MULTIPLE_CUSTOMER_ID_ON_EMAIL` - Riskiness of email address having many customer IDs.
 * * `ORDER_AMOUNT` - Riskiness of the order amount.
 * * `ORG_DISTANCE_RISK` - Risk of ISP and distance between billing address and IP location.
 * * `PHONE` - Riskiness of the phone number or related numbers.
 * * `CART` - Riskiness of shopping cart contents.
 * * `TIME_OF_DAY` - Risk due to local time of day.
 * * `TRANSACTION_REPORT_EMAIL` - Risk due to transaction reports on the email address.
 * * `TRANSACTION_REPORT_IP` - Risk due to transaction reports on the IP address.
 * * `TRANSACTION_REPORT_PHONE` - Risk due to transaction reports on the phone number.
 * * `TRANSACTION_REPORT_SHIP` - Risk due to transaction reports on the shipping address.
 * * `EMAIL_ACTIVITY` - Riskiness of the email address based on minFraud network activity.
 * * `PHONE_ACTIVITY` - Riskiness of the phone number based on minFraud network activity.
 * * `SHIP_ACTIVITY` - Riskiness of ship address based on minFraud network activity.
 */
class Reason implements \JsonSerializable
{
    /**
     * @var string This value is a machine-readable code identifying the reason
     */
    public readonly string $code;

    /**
     * @var string This value provides a human-readable explanation of the reason. The description
     *             may change at any time and should not be matched against.
     */
    public readonly string $reason;

    public function __construct(array $response)
    {
        $this->code = $response['code'];
        $this->reason = $response['reason'];
    }

    public function jsonSerialize(): array
    {
        $js = [];

        $js['code'] = $this->code;
        $js['reason'] = $this->reason;

        return $js;
    }
}
