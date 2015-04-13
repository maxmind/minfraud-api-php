<?php

namespace MaxMind\WebService;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\WebServiceException;
use MaxMind\Exception\OutOfCreditsException;

/**
 * This class is not intended to be used directly by an end-user of a
 * MaxMind web service. Please use the appropriate client API for the service
 * that you are using.
 * @package MaxMind\WebService
 */
class Client
{

    private $userId;
    private $licenseKey;
    private $userAgent = 'MaxMind Web Service PHP Client';
    private $host = 'ct5-test.maxmind.com';
    private $httpRequestClass = 'MaxMind\WebService\Http\CurlRequest';
    private $timeout;
    private $connectTimeout;
    private $caBundle;

    /**
     * @param int $userId
     * @param string $licenseKey
     */
    public function __construct(
        $userId,
        $licenseKey,
        $options = array()
    ) {
        $this->userId = $userId;
        $this->licenseKey = $licenseKey;
        if (isset($options['httpRequestClass'])) {
            $this->httpRequestClass = $options['httpRequestClass'];
        }
        if (isset($options['userAgent'])) {
            $this->userAgent = sprintf(
                "%s (%s)",
                $options['userAgent'],
                $this->userAgent
            );
        }
        if (isset($options['caBundle'])) {
            $this->caBundle = $options['caBundle'];
        }
        if (isset($options['connectTimeout'])) {
            $this->connectTimeout = $options['connectTimeout'];
        }
        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    /**
     * @param string $service
     * @param string $path
     * @param array $input
     * @return mixed
     * @throws HttpException
     * @throws InvalidInputException
     * @throws WebServiceException
     */
    public function post($service, $path, $input)
    {
        list($statusCode, $contentType, $body)
            = $this->makeRequest($path, $input);
        $this->handleResponse(
            $statusCode,
            $contentType,
            $body,
            $service,
            $path
        );
    }

    /**
     * @param string $path
     * @param array $input
     * @return array
     * @throws InvalidInputException
     */
    private function makeRequest($path, $input)
    {
        $body = json_encode($input);
        if ($body === false) {
            throw new InvalidInputException(
                'Error encoding input as JSON: '
                . $this->jsonErrorDescription()
            );
        }
        $headers = array(
            'Content-type: application/json',
            'Authorization: Basic '
                . base64_encode($this->userId . ':' . $this->licenseKey),
            'Accept: application/json',
        );

        $request = new $this->httpRequestClass(
            $this->urlFor($path),
            array(
                'caBundle' => $this->caBundle ?: __DIR__ . '/cacert.pem',
                'headers' => $headers,
                'userAgent' => $this->userAgent,
                'connectTimeout' => $this->connectTimeout,
                'timeout' => $this->timeout,
            )
        );

        list($statusCode, $contentType, $body) = $request->post($body);

        return array($statusCode, $contentType, $body);
    }

    private function handleResponse(
        $statusCode,
        $contentType,
        $body,
        $service,
        $path
    ) {
        if ($statusCode >= 400 && $statusCode <= 499) {
            $this->handle4xx($statusCode, $contentType, $body, $service, $path);
        } elseif ($statusCode >= 500) {
            $this->handle5xx($statusCode, $service, $path);
        } elseif ($statusCode != 200) {
            $this->handleUnexpectedStatus($statusCode, $service, $path);
        }
        return $this->handleSuccess($body, $service);
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
     * @return string
     */
    private function urlFor($path)
    {
        return 'https://' . $this->host . $path;
    }

    /**
     * @param int $statusCode
     * @param string $contentType
     * @param string $body
     * @param string $service
     * @throws AuthenticationException
     * @throws HttpException
     * @throws InvalidRequestException
     * @throws WebServiceException
     * @throws OutOfCreditsException
     */
    private function handle4xx(
        $statusCode,
        $contentType,
        $body,
        $service,
        $path
    ) {
        if (strlen($body) === 0) {
            throw new HttpException(
                "Received a $statusCode error for $service with no body",
                $statusCode,
                $this->urlFor($path)
            );
        }
        if (!strstr($contentType, 'json')) {
            throw new HttpException(
                "Received a $statusCode error for $service with " .
                "the following body: " . $body,
                $statusCode,
                $this->urlFor($path)
            );
        }

        $message = json_decode($body, true);
        if ($message === null) {
            throw new HttpException(
                "Received a $statusCode error for $service but " .
                "it did not include the expected JSON body: " .
                $body,
                $statusCode,
                $this->urlFor($path)
            );
        }

        if (!isset($message['code']) || !isset($message['error'])) {
            throw new WebServiceException(
                'Error response contains JSON but it does not ' .
                'specify code or error keys: ' . $body
            );
        }

        $this->handleWebServiceError(
            $message['error'],
            $message['code'],
            $statusCode,
            $service,
            $path
        );
    }

    /**
     * @param string $message
     * @param string $code
     * @param int $statusCode
     * @param string $service
     * @param string $path
     * @throws AuthenticationException
     * @throws InvalidRequestException
     * @throws OutOfCreditsException
     */
    private function handleWebServiceError(
        $message,
        $code,
        $statusCode,
        $service,
        $path
    ) {
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
                    $this->urlFor($path)
                );
        }
    }

    /**
     * @param int $statusCode
     * @param string $service
     * @param string $path
     * @throws HttpException
     */
    private function handle5xx($statusCode, $service, $path)
    {
        throw new HttpException(
            "Received a server error ($statusCode) for $service",
            $statusCode,
            $this->urlFor($path)
        );
    }

    /**
     * @param int $statusCode
     * @param string $service
     * @param string $path
     * @throws HttpException
     */
    private function handleUnexpectedStatus($statusCode, $service, $path)
    {
        throw new HttpException(
            'Received an unexpected HTTP status ' .
            "($statusCode) for $service",
            $statusCode,
            $this->urlFor($path)
        );
    }

    /**
     * @param string $body
     * @param string $service
     * @return array
     * @throws WebServiceException
     */
    private function handleSuccess($body, $service)
    {
        if (strlen($body) == 0) {
            // XXX - specific exceptions
            throw new WebServiceException(
                "Received a 200 response for $service but did not " .
                "receive a HTTP body."
            );
        }

        $decodedContent = json_decode($body, true);
        if ($decodedContent === null) {
            throw new WebServiceException(
                "Received a 200 response for $service but could " .
                'not decode the response as JSON: '
                . $this->jsonErrorDescription() . ' Body: ' .$body
            );
        }

        return $decodedContent;
    }
}
