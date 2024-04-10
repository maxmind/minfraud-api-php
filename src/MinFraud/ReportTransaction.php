<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

use MaxMind\Exception\AuthenticationException;
use MaxMind\Exception\HttpException;
use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\InvalidRequestException;
use MaxMind\Exception\WebServiceException;

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
     * @throws InvalidInputException   when the request has missing or invalid
     *                                 data
     * @throws AuthenticationException when there is an issue authenticating
     *                                 the request
     * @throws InvalidRequestException when the request is invalid for some
     *                                 other reason, e.g., invalid JSON in the
     *                                 POST.
     * @throws HttpException           when an unexpected HTTP error occurs
     * @throws WebServiceException     when some other error occurs. This also
     *                                 serves as the base class for the above
     *                                 exceptions.
     */
    public function report(
        array $values = [],
        ?string $chargebackCode = null,
        ?string $ipAddress = null,
        ?string $maxmindId = null,
        ?string $minfraudId = null,
        ?string $notes = null,
        ?string $tag = null,
        ?string $transactionId = null
    ): void {
        if (\count($values) !== 0) {
            if (\func_num_args() !== 1) {
                throw new \InvalidArgumentException(
                    'You may only provide the $values array or named arguments, not both.',
                );
            }

            $chargebackCode = $this->remove($values, 'chargeback_code');
            $ipAddress = $this->remove($values, 'ip_address');
            $maxmindId = $this->remove($values, 'maxmind_id');
            $minfraudId = $this->remove($values, 'minfraud_id');
            $notes = $this->remove($values, 'notes');
            $tag = $this->remove($values, 'tag');
            $transactionId = $this->remove($values, 'transaction_id');

            $this->verifyEmpty($values);
        }

        if ($chargebackCode !== null) {
            $values['chargeback_code'] = $chargebackCode;
        }

        if ($ipAddress === null) {
            // This is required so we always throw an exception if it is not set
            throw new InvalidInputException('An IP address is required');
        }
        if (!filter_var($ipAddress, \FILTER_VALIDATE_IP)) {
            $this->maybeThrowInvalidInputException("$ipAddress is an invalid IP address");
        }
        $values['ip_address'] = $ipAddress;

        if ($maxmindId !== null) {
            if (\strlen($maxmindId) !== 8) {
                $this->maybeThrowInvalidInputException("$maxmindId must be 8 characters long");
            }
            $values['maxmind_id'] = $maxmindId;
        }

        if ($minfraudId !== null) {
            if (!preg_match(
                '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i',
                $minfraudId,
            )) {
                $this->maybeThrowInvalidInputException("$minfraudId must be a valid minFraud ID");
            }

            $values['minfraud_id'] = $minfraudId;
        }

        if ($notes !== null) {
            $values['notes'] = $notes;
        }

        if ($tag === null) {
            // This is required so we always throw an exception if it is not set
            throw new InvalidInputException('A tag is required');
        }
        if (!\in_array($tag, ['not_fraud', 'suspected_fraud', 'spam_or_abuse', 'chargeback'], true)) {
            $this->maybeThrowInvalidInputException(
                "$tag must be one of 'not_fraud', 'suspected_fraud', 'spam_or_abuse', or 'chargeback'",
            );
        }
        $values['tag'] = $tag;

        if ($transactionId !== null) {
            $values['transaction_id'] = $transactionId;
        }

        $url = self::$basePath . 'transactions/report';
        $this->client->post('ReportTransaction', $url, $values);
    }
}
