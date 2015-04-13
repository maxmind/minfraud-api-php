<?php

namespace MaxMind;

use MaxMind\Exception\InvalidInputException;
use MaxMind\WebService\Client;

/**
 * Class MinFraud
 * @package MaxMind
 */
class MinFraud
{

    private $client;
    private $basePath = '/minfraud/v2.0/';
    private $content;

    /**
     * @param int $userId
     * @param string $licenseKey
     * @param array $content
     */
    public function __construct(
        $userId,
        $licenseKey,
        $content = array()
    ) {
        $this->client = new Client($userId, $licenseKey);
        $this->content = $content;
    }

    /**
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
     * @return MinFraud\Model\Score
     * @throws InvalidInputException
     * @internal param array $input
     */
    public function score()
    {
        return $this->post('Score');
    }

    /**
     * @return MinFraud\Model\Insights
     * @throws InvalidInputException
     * @internal param array $input
     */
    public function insights()
    {
        return $this->post('Insights');
    }

    /**
     * @param $service
     * @return mixed
     * @throws InvalidInputException
     */
    private function post($service)
    {
        if (!isset($this->content['device']['ip_address'])) {
            throw new InvalidInputException(
                'The device "ip_address" field is required'
            );
        }
        $url = $this->basePath . strtolower($service);
        $class = "MaxMind\\MinFraud\\Model\\" . $service;
        return new $class($this->client->post($service, $url, $this->content));
    }
}
