<?php

namespace MaxMind;

use MaxMind\MinFraud\Exception\AuthenticationException;
use MaxMind\MinFraud\Exception\HttpException;
use MaxMind\MinFraud\Exception\InvalidInputException;
use MaxMind\MinFraud\Exception\InvalidRequestException;
use MaxMind\MinFraud\Exception\MinFraudException;
use MaxMind\MinFraud\Exception\OutOfCreditsException;

/**
 * Class MinFraud
 * @package MaxMind
 */
class MinFraud
{

    private $userId;
    private $licenseKey;
    private $userAgent = 'mf2 php dev';
    private $basePath = '/minfraud/v2.0/';
    private $host = 'ct5-test.maxmind.com';

    /**
     * @param int $userId
     * @param string $licenseKey
     */
    public function __construct(
        $userId,
        $licenseKey
    ) {
        $this->userId = $userId;
        $this->licenseKey = $licenseKey;
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Score
     */
    public function score($input)
    {
        return $this->responseFor('Score', $input);
    }

    /**
     * @param string $service
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Score|\MaxMind\MinFraud\Model\Insights
     * @throws HttpException
     * @throws InvalidInputException
     * @throws MinFraudException
     */
    private function responseFor($service, $input)
    {
        list($statusCode, $contentType, $body)
            = $this->makeRequest($service, $input);

        if ($statusCode >= 400 && $statusCode <= 499) {
            $this->handle4xx($statusCode, $contentType, $body, $service);
        } elseif ($statusCode >= 500) {
            $this->handle5xx($statusCode, $service);
        } elseif ($statusCode != 200) {
            $this->handleUnexpectedStatus($statusCode, $service);
        }
        return $this->handleSuccess($body, $service);
    }

    /**
     * @param string $service
     * @param array $input
     * @return array
     * @throws InvalidInputException
     */
    private function makeRequest($service, $input)
    {
        $body = json_encode($input);
        if ($body === false) {
            throw new InvalidInputException(
                'Error encoding input as JSON: '
                . $this->jsonErrorDescription()
            );
        }

        $curl = curl_init();
        $opts[CURLOPT_POST] = true;
        $opts[CURLOPT_POSTFIELDS] = $body;

        $opts[CURLOPT_HTTPHEADER] = array(
            'Content-type: application/json',
            'Authorization: Basic '
                . base64_encode($this->userId . ':' . $this->licenseKey),
            'Accept: application/json',
        );

        $opts[CURLOPT_URL] = $this->urlFor($service);

        $opts[CURLOPT_USERAGENT] = $this->userAgent;
        $opts[CURLOPT_FOLLOWLOCATION] = false;
        $opts[CURLOPT_SSL_VERIFYPEER] = true;

        $opts[CURLOPT_RETURNTRANSFER] = true;
        // SET CURLOPT_CAINFO

        curl_setopt_array($curl, $opts);
        $body = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);


        curl_close($curl);
        return array($statusCode, $contentType, $body);
    }


    // I'm not yet sure if we want to use Guzzle or just do it manually.
    // Guzzle has sort of been a pain as the newer versions do not support
    // PHP 5.3 and there has been quite a bit of API change. If we don't use
    // Guzzle, we should probably move GeoIP2 away from Guzzle as this depends
    // on that for the model classes.

    /**
     * @return string
     */
    private function jsonErrorDescription()
    {
        $errno = json_last_error();
        switch ($errno) {
            case JSON_ERROR_DEPTH:
                return 'The maximum stack depth has been exceeded.';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Invalid or malformed JSON.';
            case JSON_ERROR_CTRL_CHAR:
                return 'Control character error.';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error.';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters.';
            default:
                return "Other JSON error ($errno).";
        }
    }

    /**
     * @param string $service
     * @return string
     */
    private function urlFor($service)
    {
        return 'https://' . $this->host . $this->basePath
            . strtolower($service);
    }

    /**
     * @param int $statusCode
     * @param string $contentType
     * @param string $body
     * @param string $service
     * @throws AuthenticationException
     * @throws HttpException
     * @throws InvalidRequestException
     * @throws MinFraudException
     * @throws OutOfCreditsException
     */
    private function handle4xx($statusCode, $contentType, $body, $service)
    {
        if (strlen($body) === 0) {
            throw new HttpException(
                "Received a $statusCode error for minFraud $service with no body",
                $statusCode,
                $this->urlFor($service)
            );
        }
        if (!strstr($contentType, 'json')) {
            throw new HttpException(
                "Received a $statusCode error for minFraud $service with " .
                "the following body: " . $body,
                $statusCode,
                $this->urlFor($service)
            );
        }

        $message = json_decode($body, true);
        if ($message === null) {
            throw new HttpException(
                "Received a $statusCode error for minFraud $service but " .
                "it did not include the expected JSON body: " .
                $body,
                $statusCode,
                $this->urlFor($service)
            );
        }

        if (!isset($message['code']) || !isset($message['error'])) {
            throw new MinFraudException(
                'Error response contains JSON but it does not ' .
                'specify code or error keys: ' . $body
            );
        }

        $this->handleWebServiceError(
            $message['error'],
            $message['code'],
            $statusCode,
            $service
        );
    }

    /**
     * @param $message
     * @param $code
     * @param $statusCode
     * @param $service
     * @throws AuthenticationException
     * @throws InvalidRequestException
     * @throws OutOfCreditsException
     */
    private function handleWebServiceError($message, $code, $statusCode, $service)
    {
        switch ($code) {
            case 'AUTHORIZATION_INVALID':
            case 'LICENSE_KEY_REQUIRED':
            case 'USER_ID_REQUIRED':
                throw new AuthenticationException($message);
            case 'INSUFFICIENT_CREDITS':
                throw new OutOfCreditsException($message);
            default:
                throw new InvalidRequestException(
                    $message,
                    $code,
                    $statusCode,
                    $this->urlFor($service)
                );
        }
    }

    /**
     * @param $statusCode
     * @param $service
     * @throws HttpException
     */
    private function handle5xx($statusCode, $service)
    {
        throw new HttpException(
            "Received a server error ($statusCode) for minFraud $service",
            $statusCode,
            $this->urlFor($service)
        );
    }

    /**
     * @param $statusCode
     * @param $service
     * @throws HttpException
     */
    private function handleUnexpectedStatus($statusCode, $service)
    {
        throw new HttpException(
            'Received an expected HTTP status ' .
            "($statusCode) for minFraud $service",
            $statusCode,
            $this->urlFor($service)
        );
    }

    /**
     * @param string $body
     * @param string $service
     * @return \MaxMind\MinFraud\Model\Score|\MaxMind\MinFraud\Model\Insights
     * @throws MinFraudException
     */
    private function handleSuccess($body, $service)
    {
        print($body);
        if (strlen($body) == 0) {
            // XXX - specific exceptions
            throw new MinFraudException(
                "Received a 200 response for minFraud $service but did not " .
                "receive a HTTP body."
            );
        }

        $decodedContent = json_decode($body, true);
        if ($decodedContent === null) {
            throw new MinFraudException(
                "Received a 200 response for minFraud $service but could " .
                'not decode the response as JSON: '
                . $this->jsonErrorDescription() . ' Body: ' .$body
            );
        }

        $class = "MaxMind\\MinFraud\\Model\\" . $service;
        return new $class($decodedContent);
    }

    /**
     * @param array $input
     * @return \MaxMind\MinFraud\Model\Insights
     */
    public function insights($input)
    {
        return $this->responseFor('Insights', $input);
    }
}
