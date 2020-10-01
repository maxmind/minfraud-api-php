<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

use MaxMind\Exception\InvalidInputException;

class ReportTransaction extends ServiceClient
{
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
        parent::__construct($accountId, $licenseKey, $options);
    }

    /**
     * @param array $values the transaction parameters
     *
     * @throws \MaxMind\Exception\InvalidInputException   when the request has missing or invalid
     *                                                    data
     * @throws \MaxMind\Exception\AuthenticationException when there is an issue authenticating the
     *                                                    request
     * @throws \MaxMind\Exception\InvalidRequestException when the request is invalid for some
     *                                                    other reason, e.g., invalid JSON in the POST.
     * @throws \MaxMind\Exception\HttpException           when an unexpected HTTP error occurs
     * @throws \MaxMind\Exception\WebServiceException     when some other error occurs. This also
     *                                                    serves as the base class for the above exceptions.
     */
    public function report(array $values): void
    {
        $values = $this->cleanAndValidate('TransactionReport', $values);

        if (!isset($values['ip_address'])) {
            throw new InvalidInputException('Key ip_address must be present in request');
        }
        if (!isset($values['tag'])) {
            throw new InvalidInputException('Key tag must be present in request');
        }

        $url = self::$basePath . 'transactions/report';
        $this->client->post('ReportTransaction', $url, $values);
    }
}
