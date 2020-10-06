<?php

declare(strict_types=1);

namespace MaxMind;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InsufficientFundsException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\WebServiceException;

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
    private $content;
    private $locales;

    /**
     * @param int    $accountId  Your MaxMind account ID
     * @param string $licenseKey Your MaxMind license key
     * @param array  $options    An array of options. Possible keys:
     *
     * * `host` - The host to use when connecting to the web service.
     * * `userAgent` - The prefix for the User-Agent header to use in the
     *   request.
     * * `caBundle` - The bundle of CA root certificates to use in the request.
     * * `connectTimeout` - The connect timeout to use for the request.
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
     * @link https://dev.maxmind.com/minfraud/ minFraud API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function with(array $values): self
    {
        $values = $this->cleanAndValidate('Transaction', $values);

        $new = clone $this;
        $new->content = $values;

        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `device` array set to
     * `$values`. Existing `device` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Device_device
     *     minFraud device API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withDevice(array $values): self
    {
        return $this->validateAndAdd('Device', 'device', $values);
    }

    /**
     * This returns a `MinFraud` object with the `events` array set to
     * `$values`. Existing `event` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Event_event
     *     minFraud event API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withEvent(array $values): self
    {
        return $this->validateAndAdd('Event', 'event', $values);
    }

    /**
     * This returns a `MinFraud` object with the `account` array set to
     * `$values`. Existing `account` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Account_account
     *     minFraud account API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withAccount(array $values): self
    {
        return $this->validateAndAdd('Account', 'account', $values);
    }

    /**
     * This returns a `MinFraud` object with the `email` array set to
     * `$values`. Existing `email` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Email_email
     *     minFraud email API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withEmail(array $values): self
    {
        return $this->validateAndAdd('Email', 'email', $values);
    }

    /**
     * This returns a `MinFraud` object with the `billing` array set to
     * `$values`. Existing `billing` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Billing_billing
     *     minFraud billing API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withBilling(array $values): self
    {
        return $this->validateAndAdd('Billing', 'billing', $values);
    }

    /**
     * This returns a `MinFraud` object with the `shipping` array set to
     * `$values`. Existing `shipping` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Shipping_shipping
     *     minFraud shipping API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withShipping(array $values): self
    {
        return $this->validateAndAdd('Shipping', 'shipping', $values);
    }

    /**
     * This returns a `MinFraud` object with the `payment` array set to
     * `$values`. Existing `payment` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Payment_payment
     *     minFraud payment API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withPayment(array $values): self
    {
        return $this->validateAndAdd('Payment', 'payment', $values);
    }

    /**
     * This returns a `MinFraud` object with the `credit_card` array set to
     * `$values`. Existing `credit_card` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Credit_Card_credit_card
     *     minFraud credit_card API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withCreditCard(array $values): self
    {
        return $this->validateAndAdd('CreditCard', 'credit_card', $values);
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
        return $this->validateAndAdd('CustomInputs', 'custom_inputs', $values);
    }

    /**
     * This returns a `MinFraud` object with the `order` array set to
     * `$values`. Existing `order` data will be replaced.
     *
     * @link https://dev.maxmind.com/minfraud/#Order_order
     *     minFraud order API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withOrder(array $values): self
    {
        return $this->validateAndAdd('Order', 'order', $values);
    }

    /**
     * This returns a `MinFraud` object with `$values` added to the shopping
     * cart array.
     *
     * @link https://dev.maxmind.com/minfraud/#Shopping_Cart_Item
     *     minFraud shopping cart item API docs
     *
     * @return MinFraud A new immutable MinFraud object. This object is
     *                  a clone of the original with additional data.
     */
    public function withShoppingCartItem(array $values): self
    {
        $values = $this->cleanAndValidate('ShoppingCartItem', $values);

        $new = clone $this;
        if (!isset($new->content['shopping_cart'])) {
            $new->content['shopping_cart'] = [];
        }
        array_push($new->content['shopping_cart'], $values);

        return $new;
    }

    /**
     * @param string $className The name of the class (but not the namespace)
     * @param string $key       The key in the transaction array to set
     * @param array  $values    The values to validate
     *
     * @throws InvalidInputException when $values does not validate
     */
    private function validateAndAdd(string $className, string $key, array $values): self
    {
        $values = $this->cleanAndValidate($className, $values);
        $new = clone $this;
        $new->content[$key] = $values;

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
     * @return \MaxMind\MinFraud\Model\Score minFraud Score model object
     */
    public function score(): \MaxMind\MinFraud\Model\Score
    {
        return $this->post('Score');
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
     * @return \MaxMind\MinFraud\Model\Insights minFraud Insights model object
     */
    public function insights(): \MaxMind\MinFraud\Model\Insights
    {
        return $this->post('Insights');
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
     * @return \MaxMind\MinFraud\Model\Factors minFraud Factors model object
     */
    public function factors(): \MaxMind\MinFraud\Model\Factors
    {
        return $this->post('Factors');
    }

    /**
     * @param string $service the name of the service to use
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
    private function post(string $service)
    {
        $url = self::$basePath . strtolower($service);
        $class = 'MaxMind\\MinFraud\\Model\\' . $service;

        return new $class(
            $this->client->post($service, $url, $this->content),
            $this->locales
        );
    }
}
