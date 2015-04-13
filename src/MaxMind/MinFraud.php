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
     */
    public function __construct(
        $userId,
        $licenseKey,
        $content = array()
    ) {
        $this->client = new Client($userId, $licenseKey);
        $this->content = $content;
    }

    public function withEvent($values)
    {
        $new = clone $this;
        $new->content['event'] = $values;
        return $new;
    }

    public function withAccount($values)
    {
        $new = clone $this;
        $new->content['account'] = $values;
        return $new;
    }

    public function withEmail($values)
    {
        $new = clone $this;
        $new->content['email'] = $values;
        return $new;
    }

    public function withBilling($values)
    {
        $new = clone $this;
        $new->content['billing'] = $values;
        return $new;
    }


    public function withShipping($values)
    {
        $new = clone $this;
        $new->content['shipping'] = $values;
        return $new;
    }

    public function withPayment($values)
    {
        $new = clone $this;
        $new->content['payment'] = $values;
        return $new;
    }

    public function withCreditCard($values)
    {
        $new = clone $this;
        $new->content['credit_card'] = $values;
        return $new;
    }

    public function withOrder($values)
    {
        $new = clone $this;
        $new->content['order'] = $values;
        return $new;
    }

    public function withShoppingCartItem($values)
    {
        $new = clone $this;
        if (!isset($new->content['shopping_cart'])) {
            $new->content['shopping_cart'] = array();
        }
        array_push($new->content['shopping_cart'], $values);
        return $new;
    }

    public function withDevice($values)
    {
        $new = clone $this;
        $new->content['device'] = $values;
        return $new;
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Score
     */
    public function score()
    {
        return $this->post('Score');
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Insights
     */
    public function insights()
    {
        return $this->post('Insights');
    }

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
