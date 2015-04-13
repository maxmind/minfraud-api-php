<?php

namespace MaxMind;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\MinFraudException;
use MaxMind\Exception\OutOfCreditsException;
use MaxMind\WebService\Client;

/**
 * Class MinFraud
 * @package MaxMind
 */
class MinFraud
{

    private $client;
    private $basePath = '/minfraud/v2.0/';

    /**
     * @param int $userId
     * @param string $licenseKey
     */
    public function __construct(
        $userId,
        $licenseKey
    ) {
        $this->client = new Client($userId, $licenseKey);
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Score
     */
    public function score($input)
    {
        return $this->post('Score', $input);
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Insights
     */
    public function insights($input)
    {
        return $this->post('Insights', $input);
    }

    private function post($service, $input)
    {
        $url = $this->basePath . strtolower($service);
        $class = "MaxMind\\MinFraud\\Model\\" . $service;
        return new $class($this->client->post($service, $url, $input));
    }
}
