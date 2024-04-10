<?php

declare(strict_types=1);

namespace MaxMind;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InsufficientFundsException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\WebServiceException;
use MaxMind\MinFraud\Model\Factors;
use MaxMind\MinFraud\Model\Insights;
use MaxMind\MinFraud\Model\Score;
use MaxMind\MinFraud\Util;

/**
 * This class provides a client API for accessing MaxMind minFraud Score,
 * Insights and Factors.
 *
 * ## Usage ##
 *
 * The constructor takes your MaxMind account ID and license key. The object
 * returned is immutable. To build up a request, call the `->with*()` methods.
 * Each of these returns a new object (a clone of the original) with the
 * additional data. These can be chained together:
 *
 * ```
 * $client = new MinFraud(6, 'LICENSE_KEY');
 *
 * $score = $client->withDevice(['ip_address'  => '1.1.1.1',
 *                               'session_age' => 3600.5,
 *                               'session_id'  => 'foobar',
 *                               'accept_language' => 'en-US'])
 *                 ->withEmail(['domain' => 'maxmind.com'])
 *                 ->score();
 * ```
 *
 * If the request fails, an exception is thrown.
 */
class MinFraud extends MinFraud\ServiceClient
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var bool
     */
    private $hashEmail;

    /**
     * @var array<string>
     */
    private $locales;

    /**
     * @param int    $accountId  Your MaxMind account ID
     * @param string $licenseKey Your MaxMind license key
     * @param array  $options    An array of options. Possible keys:
     *
     * * `host` - The host to use when connecting to the web service.
     *   By default, the client connects to the production host. However,
     *   during testing and development, you can set this option to
     *   'sandbox.maxmind.com' to use the Sandbox environment's host. The
     *   sandbox allows you to experiment with the API without affecting your
     *   production data.
     * * `userAgent` - The prefix for the User-Agent header to use in the
     *   request.
     * * `caBundle` - The bundle of CA root certificates to use in the request.
     * * `connectTimeout` - The connect timeout to use for the request.
     * * `hashEmail` - By default, the email address is sent in plain text.
     *   If this is set to `true`, the email address will be normalized and
     *   converted to an MD5 hash before the request is sent. The email domain
     *   will continue to be sent in plain text.
     * * `timeout` - The timeout to use for the request.
     * * `proxy` - The HTTP proxy to use. May include a schema, port,
     *   username, and password, e.g., `http://username:password@127.0.0.1:10`.
     * * `locales` - An array of locale codes to use for the location name
     *   properties.
     * * `validateInput` - Default is `true`. Determines whether values passed
     *   to the `with*()` methods are validated. It is recommended that you
     *   leave validation on while developing and only (optionally) disable it
     *   before deployment.
     */
    public function __construct(
        int $accountId,
        string $licenseKey,
        array $options = []
    ) {
        $this->hashEmail = isset($options['hashEmail']) && $options['hashEmail'];

        if (isset($options['locales'])) {
            $this->locales = $options['locales'];
        } else {
            $this->locales = ['en'];
        }

        parent::__construct($accountId, $licenseKey, $options);
    }

    /**
     * This returns a `MinFraud` object with the array to be sent to the web
     * service set to `$values`. Existing values will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation?lang=en
     * minFraud API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function with(array $values): self
    {
        $new = $this;
        if (\array_key_exists('account', $values)) {
            $new = $new->withAccount($this->remove($values, 'account', ['array']));
        }
        if (\array_key_exists('billing', $values)) {
            $new = $new->withBilling($this->remove($values, 'billing', ['array']));
        }
        if (\array_key_exists('credit_card', $values)) {
            $new = $new->withCreditCard($this->remove($values, 'credit_card', ['array']));
        }
        if (\array_key_exists('custom_inputs', $values)) {
            $new = $new->withCustomInputs($this->remove($values, 'custom_inputs', ['array']));
        }
        if (\array_key_exists('device', $values)) {
            $new = $new->withDevice($this->remove($values, 'device', ['array']));
        }
        if (\array_key_exists('email', $values)) {
            $new = $new->withEmail($this->remove($values, 'email', ['array']));
        }
        if (\array_key_exists('event', $values)) {
            $new = $new->withEvent($this->remove($values, 'event', ['array']));
        }
        if (\array_key_exists('order', $values)) {
            $new = $new->withOrder($this->remove($values, 'order', ['array']));
        }
        if (\array_key_exists('payment', $values)) {
            $new = $new->withPayment($this->remove($values, 'payment', ['array']));
        }
        if (\array_key_exists('shipping', $values)) {
            $new = $new->withShipping($this->remove($values, 'shipping', ['array']));
        }
        if (\array_key_exists('shopping_cart', $values)) {
            foreach ($this->remove($values, 'shopping_cart', ['array']) as $item) {
                $new = $new->withShoppingCartItem($item);
            }
        }

        $this->verifyEmpty($values);

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `device` array set to
     * `$values`. Existing `device` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--device
     *     minFraud device API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withDevice(
        array $values = [],
        ?string $acceptLanguage = null,
        ?string $ipAddress = null,
        ?float $sessionAge = null,
        ?string $sessionId = null,
        ?string $userAgent = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }
            $acceptLanguage = $this->remove($values, 'accept_language');
            $ipAddress = $this->remove($values, 'ip_address');
            if (($v = $this->remove($values, 'session_age', ['double', 'float', 'integer', 'string'])) && $v !== null) {
                if (!is_numeric($v)) {
                    $this->maybeThrowInvalidInputException('Expected session_age to be a number');
                }
                $sessionAge = (float) $v;
            }
            if (isset($values['session_id'])) {
                if (($v = $this->remove($values, 'session_id', ['integer', 'string'])) && $v !== null) {
                    $sessionId = (string) $v;
                }
            }
            if ($sessionId) {
                $userAgent = $this->remove($values, 'user_agent');
            }

            $this->verifyEmpty($values);
        }

        if ($acceptLanguage !== null) {
            $values['accept_language'] = $acceptLanguage;
        }

        if ($ipAddress !== null) {
            if (!filter_var($ipAddress, \FILTER_VALIDATE_IP)) {
                $this->maybeThrowInvalidInputException("$ipAddress is an invalid IP address");
            }
            $values['ip_address'] = $ipAddress;
        }

        if ($sessionAge !== null) {
            if ($sessionAge < 0) {
                $this->maybeThrowInvalidInputException("Session age ($sessionAge) must be greater than or equal to 0");
            }
            $values['session_age'] = $sessionAge;
        }

        if ($sessionId !== null) {
            if (!\is_string($sessionId)
                || $sessionId === ''
                || \strlen($sessionId) > 255) {
                $this->maybeThrowInvalidInputException(
                    "Session ID ($sessionId) must be a string with length between 1 and 255",
                );
            }
            $values['session_id'] = $sessionId;
        }

        if ($userAgent !== null) {
            $values['user_agent'] = $userAgent;
        }

        $new = clone $this;
        $new->content['device'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `events` array set to
     * `$values`. Existing `event` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--event
     *     minFraud event API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withEvent(
        array $values = [],
        ?string $shopId = null,
        ?string $time = null,
        ?string $transactionId = null,
        ?string $type = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }
            $shopId = $this->remove($values, 'shop_id');
            $time = $this->remove($values, 'time');
            $transactionId = $this->remove($values, 'transaction_id');
            $type = $this->remove($values, 'type');

            $this->verifyEmpty($values);
        }

        if ($shopId !== null) {
            $values['shop_id'] = $shopId;
        }

        if ($time !== null) {
            if (\DateTime::createFromFormat(\DateTime::RFC3339, $time) === false
                && \DateTime::createFromFormat(\DateTime::RFC3339_EXTENDED, $time) === false
            ) {
                $this->maybeThrowInvalidInputException("$time is not a valid RFC 3339 formatted datetime string");
            }

            $values['time'] = $time;
        }

        if ($transactionId !== null) {
            $values['transaction_id'] = $transactionId;
        }

        if ($type !== null) {
            if (!\in_array($type, [
                'account_creation',
                'account_login',
                'email_change',
                'password_reset',
                'payout_change',
                'purchase',
                'recurring_purchase',
                'referral',
                'survey',
            ], true)) {
                $this->maybeThrowInvalidInputException("$type is not a valid event type");
            }
            $values['type'] = $type;
        }

        $new = clone $this;
        $new->content['event'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `account` array set to
     * `$values`. Existing `account` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--account
     *     minFraud account API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withAccount(
        array $values = [],
        ?string $userId = null,
        ?string $usernameMd5 = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }
            $userId = $this->remove($values, 'user_id');
            $usernameMd5 = $this->remove($values, 'username_md5');

            $this->verifyEmpty($values);
        }

        if ($userId !== null) {
            $values['user_id'] = $userId;
        }

        if ($usernameMd5 !== null) {
            if (!preg_match('/^[a-fA-F0-9]{32}$/', $usernameMd5)) {
                $this->maybeThrowInvalidInputException("$usernameMd5 must be an MD5");
            }
            $values['username_md5'] = $usernameMd5;
        }

        $new = clone $this;
        $new->content['account'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `email` array set to
     * `$values`. Existing `email` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--email
     *     minFraud email API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withEmail(
        array $values = [],
        ?string $address = null,
        ?string $domain = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }
            $address = $this->remove($values, 'address');
            $domain = $this->remove($values, 'domain');

            $this->verifyEmpty($values);
        }

        if ($address !== null) {
            if (!filter_var($address, \FILTER_VALIDATE_EMAIL)
                && !preg_match('/^[a-fA-F0-9]{32}$/', $address)) {
                $this->maybeThrowInvalidInputException("$address is an invalid email address or MD5");
            }
            $values['address'] = $address;
        }

        if ($domain !== null) {
            if (!filter_var($domain, \FILTER_VALIDATE_DOMAIN, \FILTER_FLAG_HOSTNAME) || !str_contains($domain, '.')) {
                $this->maybeThrowInvalidInputException("$domain is an invalid domain name");
            }
            $values['domain'] = $domain;
        }

        $new = clone $this;
        $new->content['email'] = $values;

        if ($this->hashEmail) {
            $new->content = Util::maybeHashEmail($new->content);
        }

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `billing` array set to
     * `$values`. Existing `billing` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--billing
     *     minFraud billing API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withBilling(
        array $values = [],
        ?string $address = null,
        ?string $address2 = null,
        ?string $city = null,
        ?string $company = null,
        ?string $country = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phoneCountryCode = null,
        ?string $phoneNumber = null,
        ?string $postal = null,
        ?string $region = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $address = $this->remove($values, 'address');
            $address2 = $this->remove($values, 'address_2');
            $city = $this->remove($values, 'city');
            $company = $this->remove($values, 'company');
            $country = $this->remove($values, 'country');
            $firstName = $this->remove($values, 'first_name');
            $lastName = $this->remove($values, 'last_name');
            $phoneCountryCode = $this->remove($values, 'phone_country_code');
            $phoneNumber = $this->remove($values, 'phone_number');
            $postal = $this->remove($values, 'postal');
            $region = $this->remove($values, 'region');

            $this->verifyEmpty($values);
        }

        if ($address !== null) {
            $values['address'] = $address;
        }

        if ($address2 !== null) {
            $values['address_2'] = $address2;
        }

        if ($city !== null) {
            $values['city'] = $city;
        }

        if ($company !== null) {
            $values['company'] = $company;
        }

        if ($country !== null) {
            if (!preg_match('/^[A-Z]{2}$/', $country)) {
                $this->maybeThrowInvalidInputException("$country is not a valid ISO 3166-1 country code");
            }
            $values['country'] = $country;
        }

        if ($firstName !== null) {
            $values['first_name'] = $firstName;
        }

        if ($lastName !== null) {
            $values['last_name'] = $lastName;
        }

        if ($phoneCountryCode !== null) {
            if (!preg_match('/^[0-9]{1,4}$/', $phoneCountryCode)) {
                $this->maybeThrowInvalidInputException('Phone country code must be a string of 1 to 4 digits.');
            }
            $values['phone_country_code'] = $phoneCountryCode;
        }

        if ($phoneNumber !== null) {
            $values['phone_number'] = $phoneNumber;
        }

        if ($postal !== null) {
            $values['postal'] = $postal;
        }

        if ($region !== null) {
            if (!preg_match('/^[0-9A-Z]{1,4}$/', $region)) {
                $this->maybeThrowInvalidInputException(
                    "$region is not a valid ISO 3166-2 region code (without country prefix)",
                );
            }
            $values['region'] = $region;
        }

        $new = clone $this;
        $new->content['billing'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `shipping` array set to
     * `$values`. Existing `shipping` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--shipping
     *     minFraud shipping API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withShipping(
        array $values = [],
        ?string $address = null,
        ?string $address2 = null,
        ?string $city = null,
        ?string $company = null,
        ?string $country = null,
        ?string $deliverySpeed = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $phoneCountryCode = null,
        ?string $phoneNumber = null,
        ?string $postal = null,
        ?string $region = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $address = $this->remove($values, 'address');
            $address2 = $this->remove($values, 'address_2');
            $city = $this->remove($values, 'city');
            $company = $this->remove($values, 'company');
            $country = $this->remove($values, 'country');
            $deliverySpeed = $this->remove($values, 'delivery_speed');
            $firstName = $this->remove($values, 'first_name');
            $lastName = $this->remove($values, 'last_name');
            $phoneCountryCode = $this->remove($values, 'phone_country_code');
            $phoneNumber = $this->remove($values, 'phone_number');
            $postal = $this->remove($values, 'postal');
            $region = $this->remove($values, 'region');

            $this->verifyEmpty($values);
        }

        if ($address !== null) {
            $values['address'] = $address;
        }

        if ($address2 !== null) {
            $values['address_2'] = $address2;
        }

        if ($city !== null) {
            $values['city'] = $city;
        }

        if ($company !== null) {
            $values['company'] = $company;
        }

        if ($country !== null) {
            if (!preg_match('/^[A-Z]{2}$/', $country)) {
                $this->maybeThrowInvalidInputException("$country is not a valid ISO 3166-1 country code");
            }
            $values['country'] = $country;
        }

        if ($deliverySpeed !== null) {
            if (!\in_array($deliverySpeed, ['same_day', 'overnight', 'expedited', 'standard'], true)) {
                $this->maybeThrowInvalidInputException("$deliverySpeed is not a valid delivery speed");
            }

            $values['delivery_speed'] = $deliverySpeed;
        }

        if ($firstName !== null) {
            $values['first_name'] = $firstName;
        }

        if ($lastName !== null) {
            $values['last_name'] = $lastName;
        }

        if ($phoneCountryCode !== null) {
            if (!preg_match('/^[0-9]{1,4}$/', $phoneCountryCode)) {
                $this->maybeThrowInvalidInputException('Phone country code must be a string of 1 to 4 digits.');
            }
            $values['phone_country_code'] = $phoneCountryCode;
        }

        if ($phoneNumber !== null) {
            $values['phone_number'] = $phoneNumber;
        }

        if ($postal !== null) {
            $values['postal'] = $postal;
        }

        if ($region !== null) {
            if (!preg_match('/^[0-9A-Z]{1,4}$/', $region)) {
                $this->maybeThrowInvalidInputException("$region is not a valid ISO 3166-2 region code");
            }
            $values['region'] = $region;
        }

        $new = clone $this;
        $new->content['shipping'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `payment` array set to
     * `$values`. Existing `payment` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--payment
     *     minFraud payment API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withPayment(
        array $values = [],
        ?string $declineCode = null,
        ?string $processor = null,
        ?bool $wasAuthorized = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $declineCode = $this->remove($values, 'decline_code');
            $processor = $this->remove($values, 'processor');
            $wasAuthorized = $this->remove($values, 'was_authorized', ['boolean']);

            $this->verifyEmpty($values);
        }

        if ($declineCode !== null) {
            $values['decline_code'] = $declineCode;
        }

        if ($processor !== null) {
            if (!\in_array($processor, [
                'adyen',
                'affirm',
                'afterpay',
                'altapay',
                'amazon_payments',
                'american_express_payment_gateway',
                'apple_pay',
                'aps_payments',
                'authorizenet',
                'balanced',
                'beanstream',
                'bluepay',
                'bluesnap',
                'boacompra',
                'boku',
                'bpoint',
                'braintree',
                'cardknox',
                'cardpay',
                'cashfree',
                'ccavenue',
                'ccnow',
                'cetelem',
                'chase_paymentech',
                'checkout_com',
                'cielo',
                'collector',
                'commdoo',
                'compropago',
                'concept_payments',
                'conekta',
                'coregateway',
                'creditguard',
                'credorax',
                'ct_payments',
                'cuentadigital',
                'curopayments',
                'cybersource',
                'dalenys',
                'dalpay',
                'datacap',
                'datacash',
                'dibs',
                'digital_river',
                'dlocal',
                'dotpay',
                'ebs',
                'ecomm365',
                'ecommpay',
                'elavon',
                'emerchantpay',
                'epay',
                'eprocessing_network',
                'epx',
                'eway',
                'exact',
                'first_atlantic_commerce',
                'first_data',
                'fiserv',
                'g2a_pay',
                'global_payments',
                'gocardless',
                'google_pay',
                'heartland',
                'hipay',
                'ingenico',
                'interac',
                'internetsecure',
                'intuit_quickbooks_payments',
                'iugu',
                'klarna',
                'komoju',
                'lemon_way',
                'mastercard_payment_gateway',
                'mercadopago',
                'mercanet',
                'merchant_esolutions',
                'mirjeh',
                'mollie',
                'moneris_solutions',
                'neopay',
                'neosurf',
                'nmi',
                'oceanpayment',
                'oney',
                'onpay',
                'openbucks',
                'openpaymx',
                'optimal_payments',
                'orangepay',
                'other',
                'pacnet_services',
                'payeezy',
                'payfast',
                'paygate',
                'paylike',
                'payment_express',
                'paymentwall',
                'payone',
                'paypal',
                'payplus',
                'paysafecard',
                'paysera',
                'paystation',
                'paytm',
                'paytrace',
                'paytrail',
                'payture',
                'payu',
                'payulatam',
                'payvision',
                'payway',
                'payza',
                'pinpayments',
                'placetopay',
                'posconnect',
                'princeton_payment_solutions',
                'psigate',
                'pxp_financial',
                'qiwi',
                'quickpay',
                'raberil',
                'razorpay',
                'rede',
                'redpagos',
                'rewardspay',
                'safecharge',
                'sagepay',
                'securetrading',
                'shopify_payments',
                'simplify_commerce',
                'skrill',
                'smartcoin',
                'smartdebit',
                'solidtrust_pay',
                'sps_decidir',
                'stripe',
                'synapsefi',
                'systempay',
                'telerecargas',
                'towah',
                'transact_pro',
                'trustly',
                'trustpay',
                'tsys',
                'usa_epay',
                'vantiv',
                'verepay',
                'vericheck',
                'vindicia',
                'virtual_card_services',
                'vme',
                'vpos',
                'windcave',
                'wirecard',
                'worldpay',
            ], true)) {
                $this->maybeThrowInvalidInputException("$processor is not a valid payment processor");
            }
            $values['processor'] = $processor;
        }

        if ($wasAuthorized !== null) {
            $values['was_authorized'] = $wasAuthorized;
        }

        $new = clone $this;
        $new->content['payment'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `credit_card` array set to
     * `$values`. Existing `credit_card` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--credit-card
     *     minFraud credit_card API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withCreditCard(
        array $values = [],
        ?string $avsResult = null,
        ?string $bankName = null,
        ?string $bankPhoneCountryCode = null,
        ?string $bankPhoneNumber = null,
        ?string $country = null,
        ?string $cvvResult = null,
        ?string $issuerIdNumber = null,
        ?string $lastDigits = null,
        ?string $token = null,
        ?bool $was3dSecureSuccessful = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $values = Util::cleanCreditCard($values);

            $avsResult = $this->remove($values, 'avs_result');
            $bankName = $this->remove($values, 'bank_name');
            $bankPhoneCountryCode = $this->remove($values, 'bank_phone_country_code');
            $bankPhoneNumber = $this->remove($values, 'bank_phone_number');
            $country = $this->remove($values, 'country');
            $cvvResult = $this->remove($values, 'cvv_result');
            $issuerIdNumber = $this->remove($values, 'issuer_id_number');
            $lastDigits = $this->remove($values, 'last_digits');
            $token = $this->remove($values, 'token');
            $was3dSecureSuccessful = $this->remove($values, 'was_3d_secure_successful', ['boolean']);

            $this->verifyEmpty($values);
        }

        if ($avsResult !== null) {
            if (\strlen($avsResult) !== 1) {
                $this->maybeThrowInvalidInputException('AVS result must be a string of length 1.');
            }
            $values['avs_result'] = $avsResult;
        }

        if ($bankName !== null) {
            $values['bank_name'] = $bankName;
        }

        if ($bankPhoneCountryCode !== null) {
            if (!preg_match('/^[0-9]{1,4}$/', $bankPhoneCountryCode)) {
                $this->maybeThrowInvalidInputException('Bank phone country code must be a string of 1 to 4 digits.');
            }

            $values['bank_phone_country_code'] = $bankPhoneCountryCode;
        }

        if ($bankPhoneNumber !== null) {
            $values['bank_phone_number'] = $bankPhoneNumber;
        }

        if ($country !== null) {
            if (!preg_match('/^[A-Z]{2}$/', $country)) {
                $this->maybeThrowInvalidInputException('Country must be a valid ISO 3166-1 country code.');
            }
            $values['country'] = $country;
        }

        if ($cvvResult !== null) {
            if (\strlen($cvvResult) !== 1) {
                $this->maybeThrowInvalidInputException('CVV result must be a string of length 1.');
            }
            $values['cvv_result'] = $cvvResult;
        }

        if ($issuerIdNumber !== null) {
            if (!preg_match('/^(?:[0-9]{6}|[0-9]{8})$/', $issuerIdNumber)) {
                $this->maybeThrowInvalidInputException('Issuer ID number must be a string of 6 or 8 digits.');
            }
            $values['issuer_id_number'] = $issuerIdNumber;
        }

        if ($lastDigits !== null) {
            if (!preg_match('/^(?:[0-9]{2}|[0-9]{4})$/', $lastDigits)) {
                $this->maybeThrowInvalidInputException('Last digits must be a string of 2 or 4 digits.');
            }
            $values['last_digits'] = $lastDigits;
        }

        if ($token !== null) {
            if (!preg_match('/^[\x21-\x7E]{1,255}$/', $token)) {
                $this->maybeThrowInvalidInputException(
                    'Credit card token must be a string of 1 to 255 printable ASCII characters.',
                );
            }

            if (preg_match('/^[0-9]{1,19}$/', $token)) {
                $this->maybeThrowInvalidInputException(
                    'Credit card token cannot look like a card number or part of one.',
                );
            }

            $values['token'] = $token;
        }

        if ($was3dSecureSuccessful !== null) {
            $values['was_3d_secure_successful'] = $was3dSecureSuccessful;
        }

        $new = clone $this;
        $new->content['credit_card'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `custom_inputs` array set to
     * `$values`. Existing `custom_inputs` data will be replaced.
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withCustomInputs(array $values): self
    {
        foreach ($values as $key => $value) {
            if (\is_string($value)) {
                if (str_contains($value, "\n")) {
                    $this->maybeThrowInvalidInputException(
                        "$value is invalid. String custom input values must not contain newline characters.",
                    );
                }
                if ($value === '' || \strlen($value) > 255) {
                    $this->maybeThrowInvalidInputException(
                        "$value is invalid. String custom input values must have a length between 1 and 255.",
                    );
                }
            } elseif (is_numeric($value)) {
                if ($value < -1e13 + 1 || $value > 1e13 - 1) {
                    $this->maybeThrowInvalidInputException(
                        "$value is invalid. Numeric custom input values must be between -1e13 and 1e13.",
                    );
                }
            } elseif (!\is_bool($value)) {
                $this->maybeThrowInvalidInputException(
                    "$value is invalid. Custom input values must be strings, numbers, or booleans.",
                );
            }

            if (!preg_match('/^[a-z0-9_]{1,25}\Z/', $key)) {
                $this->maybeThrowInvalidInputException(
                    "$key is invalid. Custom input keys must be alphanumeric and have 25 characters or less.",
                );
            }
        }

        $new = clone $this;
        $new->content['custom_inputs'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `order` array set to
     * `$values`. Existing `order` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--order
     *     minFraud order API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withOrder(
        array $values = [],
        ?string $affiliateId = null,
        ?float $amount = null,
        ?string $currency = null,
        ?string $discountCode = null,
        ?bool $hasGiftMessage = null,
        ?bool $isGift = null,
        ?string $referrerUri = null,
        ?string $subaffiliateId = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $affiliateId = $this->remove($values, 'affiliate_id');
            $amount = $this->remove($values, 'amount', ['double', 'float', 'integer']);
            $currency = $this->remove($values, 'currency');
            $discountCode = $this->remove($values, 'discount_code');
            $hasGiftMessage = $this->remove($values, 'has_gift_message', ['boolean']);
            $isGift = $this->remove($values, 'is_gift', ['boolean']);
            $referrerUri = $this->remove($values, 'referrer_uri');
            $subaffiliateId = $this->remove($values, 'subaffiliate_id');

            $this->verifyEmpty($values);
        }

        if ($affiliateId !== null) {
            $values['affiliate_id'] = $affiliateId;
        }

        if ($amount !== null) {
            if ($amount < 0) {
                $this->maybeThrowInvalidInputException("$amount must be greater than or equal to 0");
            }
            $values['amount'] = $amount;
        }

        if ($currency !== null) {
            if (!preg_match('/^[A-Z]{3}$/', $currency)) {
                $this->maybeThrowInvalidInputException("$currency is not a valid currency code");
            }
            $values['currency'] = $currency;
        }

        if ($discountCode !== null) {
            $values['discount_code'] = $discountCode;
        }

        if ($hasGiftMessage !== null) {
            $values['has_gift_message'] = $hasGiftMessage;
        }

        if ($isGift !== null) {
            $values['is_gift'] = $isGift;
        }

        if ($referrerUri !== null) {
            if (!filter_var($referrerUri, \FILTER_VALIDATE_URL)) {
                $this->maybeThrowInvalidInputException("$referrerUri is not a valid URL");
            }
            $values['referrer_uri'] = $referrerUri;
        }

        if ($subaffiliateId !== null) {
            $values['subaffiliate_id'] = $subaffiliateId;
        }

        $new = clone $this;
        $new->content['order'] = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with `$values` added to the shopping
     * cart array.
     *
     * @link https://dev.maxmind.com/minfraud/api-documentation/requests?lang=en#schema--request--shopping-cart--item
     *     minFraud shopping cart item API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withShoppingCartItem(
        array $values = [],
        ?string $category = null,
        ?string $itemId = null,
        ?float $price = null,
        ?int $quantity = null,
    ): self {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $category = $this->remove($values, 'category');
            if (($v = (string) $this->remove($values, 'item_id', ['integer', 'string'])) && $v !== null) {
                $itemId = $v;
            }
            $price = $this->remove($values, 'price', ['double', 'float', 'integer']);
            $quantity = $this->remove($values, 'quantity', ['integer']);

            $this->verifyEmpty($values);
        }

        if ($category !== null) {
            $values['category'] = $category;
        }

        if ($itemId !== null) {
            $values['item_id'] = $itemId;
        }

        if ($price !== null) {
            if ($price < 0) {
                $this->maybeThrowInvalidInputException("$price must be greater than or equal to 0");
            }
            $values['price'] = $price;
        }

        if ($quantity !== null) {
            if ($quantity < 0) {
                $this->maybeThrowInvalidInputException("$quantity must be greater than or equal to 0");
            }
            $values['quantity'] = $quantity;
        }

        $new = clone $this;
        if (!isset($new->content['shopping_cart'])) {
            $new->content['shopping_cart'] = [];
        }
        $new->content['shopping_cart'][] = $values;

        return $new;
    }

    /**
     * This method performs a minFraud Score lookup using the request data in
     * the current object and returns a model object for minFraud Score.
     *
     * @throws InvalidInputException      when the request has missing or invalid
     *                                    data
     * @throws AuthenticationException    when there is an issue authenticating
     *                                    the request
     * @throws InsufficientFundsException when your account is out of funds
     * @throws InvalidRequestException    when the request is invalid for some
     *                                    other reason, e.g., invalid JSON in the POST.
     * @throws HttpException              when an unexpected HTTP error occurs
     * @throws WebServiceException        when some other error occurs. This also
     *                                    serves as the base class for the above exceptions.
     *
     * @return Score minFraud Score model object
     */
    public function score(): Score
    {
        return $this->post(Score::class, 'score');
    }

    /**
     * This method performs a minFraud Insights lookup using the request data
     * in the current object and returns a model object for minFraud Insights.
     *
     * @throws InvalidInputException      when the request has missing or invalid
     *                                    data
     * @throws AuthenticationException    when there is an issue authenticating
     *                                    the request
     * @throws InsufficientFundsException when your account is out of funds
     * @throws InvalidRequestException    when the request is invalid for some
     *                                    other reason, e.g., invalid JSON in the POST.
     * @throws HttpException              when an unexpected HTTP error occurs
     * @throws WebServiceException        when some other error occurs. This also
     *                                    serves as the base class for the above exceptions.
     *
     * @return Insights minFraud Insights model object
     */
    public function insights(): Insights
    {
        return $this->post(Insights::class, 'insights');
    }

    /**
     * This method performs a minFraud Factors lookup using the request data
     * in the current object and returns a model object for minFraud Factors.
     *
     * @throws InvalidInputException      when the request has missing or invalid
     *                                    data
     * @throws AuthenticationException    when there is an issue authenticating
     *                                    the request
     * @throws InsufficientFundsException when your account is out of funds
     * @throws InvalidRequestException    when the request is invalid for some
     *                                    other reason, e.g., invalid JSON in the POST.
     * @throws HttpException              when an unexpected HTTP error occurs
     * @throws WebServiceException        when some other error occurs. This also
     *                                    serves as the base class for the above exceptions.
     *
     * @return Factors minFraud Factors model object
     */
    public function factors(): Factors
    {
        return $this->post(Factors::class, 'factors');
    }

    /**
     * @param string $class the model class name to use
     * @param string $path  the service path suffix to use
     *
     * @throws InvalidInputException      when the request has missing or invalid
     *                                    data
     * @throws AuthenticationException    when there is an issue authenticating the
     *                                    request
     * @throws InsufficientFundsException when your account is out of funds
     * @throws InvalidRequestException    when the request is invalid for some
     *                                    other reason, e.g., invalid JSON in the POST.
     * @throws HttpException              when an unexpected HTTP error occurs
     * @throws WebServiceException        when some other error occurs. This also
     *                                    serves as the base class for the above exceptions.
     *
     * @return mixed the model class for the service
     */
    private function post(string $class, string $path)
    {
        $url = self::$basePath . $path;

        $service = 'minFraud ' . ucfirst($path);

        return new $class(
            $this->client->post($service, $url, $this->content),
            $this->locales
        );
    }
}
