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
     * This call allows you to report transactions to MaxMind for use in
     * updating the fraud score of future queries. The transaction should have
     * been previously submitted to minFraud.
     *
     * @param array  $values         An array of transaction parameters. The keys are the same
     *                               as the JSON keys. You may use either this or the named
     *                               arguments, but not both.
     * @param string $ipAddress      Optional. The IP address of the customer placing the
     *                               order. This should be passed as a string like
     *                               "44.55.66.77" or "2001:db8::2:1". This field is not
     *                               required if you provide at least one of the transaction's
     *                               maxmindId, minfraudId, or transactionId.
     * @param string $tag            Required. A string indicating the likelihood that a
     *                               transaction may be fraudulent. Possible values:
     *                               not_fraud, suspected_fraud, spam_or_abuse, or
     *                               chargeback.
     * @param string $chargebackCode Optional. A string which is provided by your payment
     *                               processor indicating the reason for the chargeback.
     * @param string $maxmindId      Optional. A unique eight character string identifying
     *                               a minFraud Standard or Premium request. These IDs are
     *                               returned in the maxmindID field of a response for a
     *                               successful minFraud request. This field is not
     *                               required if you provide at least one of the transaction's
     *                               ipAddress, minfraudId, or transactionId. You are
     *                               encouraged to provide it, if possible.
     * @param string $minfraudId     Optional. A UUID that identifies a minFraud Score,
     *                               minFraud Insights, or minFraud Factors request. This
     *                               ID is returned at /id in the response. This field is
     *                               not required if you provide at least one of the transaction's
     *                               ipAddress, maxmindId, or transactionId. You are encouraged to
     *                               provide it the request was made to one of these services.
     * @param string $notes          Optional. Your notes on the fraud tag associated with
     *                               the transaction. We manually review many reported
     *                               transactions to improve our scoring for you so any
     *                               additional details to help us understand context are
     *                               helpful.
     * @param string $transactionId  Optional. The transaction ID you originally passed to
     *                               minFraud. This field is not required if you provide at
     *                               least one of the transaction's ipAddress, maxmindId, or
     *                               minfraudId. You are encouraged to provide it or the
     *                               transaction's maxmind_id or minfraud_id.
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

        if ($ipAddress !== null) {
            if (!filter_var($ipAddress, \FILTER_VALIDATE_IP)) {
                $this->maybeThrowInvalidInputException("$ipAddress is an invalid IP address");
            }
            $values['ip_address'] = $ipAddress;
        }

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

        // One of these fields is required so we always throw an exception if one is not set
        if (($ipAddress === null || $ipAddress === '')
            && ($minfraudId === null || $minfraudId === '')
            && ($maxmindId === null || $maxmindId === '')
            && ($transactionId === null || $transactionId === '')
        ) {
            throw new InvalidInputException(
                'The user must pass at least one of the following: ' .
                'ipAddress, minfraudId, maxmindId, transactionId.'
            );
        }

        $url = self::$basePath . 'transactions/report';
        $this->client->post('ReportTransaction', $url, $values);
    }
}
