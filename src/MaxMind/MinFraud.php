<?php

namespace MaxMind;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InsufficientFundsException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\WebServiceException;
use MaxMind\WebService\Client;

/**
 * Class MinFraud
 * @package MaxMind
 *
 * This class provides the client API for access MaxMind minFraud Score and
 * Insights.
 *
 * ## Usage ##
 *
 * The constructor takes your MaxMind user ID and license key. The object
 * returned is immutable. To build up a request, call the `->with*()` methods.
 * Each of these returns a new object (a clone of the original) with the
 * addition data. These can be chained together:
 *
 * ```
 * $client = new MinFraud(6, 'LICENSE_KEY');
 *
 * $score = $client->withDevice(['ip_address' => '1.1.1.1'
 *                               'accept_language' => 'en-US'])
 *                 ->withEmail(['domain' => 'maxmind.com'])
 *                 ->score();
 * ```
 *
 * If the request fails, an exception is thrown.
 */
class MinFraud
{
    const VERSION = '0.0.1';

    private $client;
    private static $host = 'minfraud.maxmind.com';

    private static $basePath = '/minfraud/v2.0/';
    private $content;
    private $locales;

    /**
     * {@inheritdoc }
     * * `locales` - an array of locale codes to use in name property
     */
    public function __construct(
        $userId,
        $licenseKey,
        $options = array()
    ) {
        if (isset($options['locales'])) {
            $this->locales = $options['locales'];
        } else {
            $this->locales = array('en');
        }

        if (!isset($options['host'])) {
            $options['host'] = self::$host;
        }
        $options['userAgent'] = $this->userAgent();
        $this->client = new Client($userId, $licenseKey, $options);
    }

    /**
     * This returns a `MinFraud` object with the array to be sent to the web
     * service set to `$values`. Existing values will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/ minFraud API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function with($values)
    {
        $new = clone $this;
        $new->content = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `device` array set to
     * `$values`. Existing `device` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Device_device minFraud device API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withDevice($values)
    {
        $new = clone $this;
        $new->content['device'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `events` array set to
     * `$values`. Existing `event` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Event_event minFraud event API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withEvent($values)
    {
        $new = clone $this;
        $new->content['event'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `account` array set to
     * `$values`. Existing `account` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Account_account minFraud account API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withAccount($values)
    {
        $new = clone $this;
        $new->content['account'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `email` array set to
     * `$values`. Existing `email` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Email_email minFraud email API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withEmail($values)
    {
        $new = clone $this;
        $new->content['email'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `billing` array set to
     * `$values`. Existing `billing` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Billing_billing minFraud billing API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withBilling($values)
    {
        $new = clone $this;
        $new->content['billing'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `shipping` array set to
     * `$values`. Existing `shipping` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Shipping_shipping minFraud shipping API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withShipping($values)
    {
        $new = clone $this;
        $new->content['shipping'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `payment` array set to
     * `$values`. Existing `payment` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Payment_payment minFraud payment API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withPayment($values)
    {
        $new = clone $this;
        $new->content['payment'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `credit_card` array set to
     * `$values`. Existing `credit_card` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Credit_Card_credit_card minFraud credit_card API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withCreditCard($values)
    {
        $new = clone $this;
        $new->content['credit_card'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with the `order` array set to
     * `$values`. Existing `order` data will be replaced.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Order_order minFraud order API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withOrder($values)
    {
        $new = clone $this;
        $new->content['order'] = $values;
        return $new;
    }

    /**
     * This returns a `MinFraud` object with `$values` added to the shopping
     * cart array.
     * @link http://dev.maxmind.com/minfraud-score-and-insights-api-documentation/#Shopping_Cart_Item minFraud shopping cart item API docs
     *
     * @param $values
     * @return MinFraud
     */
    public function withShoppingCartItem($values)
    {
        $new = clone $this;
        if (!isset($new->content['shopping_cart'])) {
            $new->content['shopping_cart'] = array();
        }
        array_push($new->content['shopping_cart'], $values);
        return $new;
    }

    /**
     * This method does a minFraud Score lookup using the request data in the
     * current object and returns a model object for minFraud Score.
     *
     * @return MinFraud\Model\Score minFraud Score model object
     * @throws InvalidInputException when the request has missing or invalid
     * data.
     * @throws AuthenticationException when there is an issue authenticating the
     * request.
     * @throws InsufficientFundsException when your account is out of funds.
     * @throws InvalidRequestException when the request is invalid for some
     * other reason, e.g., invalid JSON in the POST.
     * @throws HttpException when an unexpected HTTP error occurs.
     * @throws WebServiceException when some other error occurs. This also
     * serves as the base class for the above exceptions.
     */
    public function score()
    {
        return $this->post('Score');
    }

    /**
     * This method does a minFraud Insights lookup using the request data in the
     * current object and returns a model object for minFraud Insights.
     *
     * @return MinFraud\Model\Insights minFraud Insights model object
     * @throws InvalidInputException when the request has missing or invalid
     * data.
     * @throws AuthenticationException when there is an issue authenticating the
     * request.
     * @throws InsufficientFundsException when your account is out of funds.
     * @throws InvalidRequestException when the request is invalid for some
     * other reason, e.g., invalid JSON in the POST.
     * @throws HttpException when an unexpected HTTP error occurs.
     * @throws WebServiceException when some other error occurs. This also
     * serves as the base class for the above exceptions.
     */
    public function insights()
    {
        return $this->post('Insights');
    }

    /**
     * @param $service $service The name of the service to use.
     * @return mixed The model class for the service
     * @throws InvalidInputException when the request has missing or invalid
     * data.
     * @throws AuthenticationException when there is an issue authenticating the
     * request.
     * @throws InsufficientFundsException when your account is out of funds.
     * @throws InvalidRequestException when the request is invalid for some
     * other reason, e.g., invalid JSON in the POST.
     * @throws HttpException when an unexpected HTTP error occurs.
     * @throws WebServiceException when some other error occurs. This also
     * serves as the base class for the above exceptions.
     */
    private function post($service)
    {
        if (!isset($this->content['device']['ip_address'])) {
            throw new InvalidInputException(
                'The device "ip_address" field is required'
            );
        }
        $url = self::$basePath . strtolower($service);
        $class = "MaxMind\\MinFraud\\Model\\" . $service;
        return new $class(
            $this->client->post($service, $url, $this->content),
            $this->locales
        );
    }

    /**
     * @return string The default User-Agent prefix
     */
    private function userAgent() {
        return 'minFraud-API/' . MinFraud::VERSION;
    }
}
