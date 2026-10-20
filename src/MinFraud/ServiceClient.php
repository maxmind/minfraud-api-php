<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

use MaxMind\Exception\InvalidInputException;
use MaxMind\Exception\WebServiceException;
use MaxMind\WebService\Client;

abstract class ServiceClient
{
    public const VERSION = 'v3.7.0';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected static $host = 'minfraud.maxmind.com';

    /**
     * @var string
     */
    protected static $basePath = '/minfraud/v2.0/';

    /**
     * @var bool
     */
    protected $validateInput = true;

    /**
     * @param int                  $accountId  your account ID
     * @param string               $licenseKey your license key
     * @param array<string, mixed> $options    options for the client
     *
     * @throws \RuntimeException   with older web-service-common releases, if HTTP client setup fails
     * @throws WebServiceException if HTTP client setup fails
     */
    public function __construct(
        int $accountId,
        string $licenseKey,
        array $options = []
    ) {
        if (!isset($options['host'])) {
            $options['host'] = self::$host;
        }
        $options['userAgent'] = $this->userAgent();
        $this->client = new Client($accountId, $licenseKey, $options);

        if (isset($options['validateInput'])) {
            $this->validateInput = $options['validateInput'];
        }
    }

    /**
     * @return string the prefix for the User-Agent header
     */
    protected function userAgent(): string
    {
        return 'minFraud-API/' . self::VERSION;
    }

    /**
     * @throws InvalidInputException if input validation is enabled
     */
    protected function maybeThrowInvalidInputException(string $msg): void
    {
        if ($this->validateInput) {
            throw new InvalidInputException($msg);
        }
    }

    /**
     * Removes a free-form string. An integer is converted to a string.
     *
     * @ignore
     *
     * @param array<string, mixed> $array the parent array
     * @param string               $key   the key to remove
     *
     * @throws InvalidInputException if the value has an unexpected type
     */
    protected function removeString(array &$array, string $key): ?string
    {
        $value = $this->remove($array, $key, ['integer', 'string']);
        if (\is_int($value)) {
            return (string) $value;
        }

        return $value;
    }

    /**
     * @ignore
     *
     * @param array<string, mixed> $array the parent array
     * @param string               $key   the key to remove
     * @param list<string>         $types the expected types. `numeric string`
     *                                    matches a string that `is_numeric()`
     *                                    accepts and that has no surrounding
     *                                    whitespace.
     *
     * @throws InvalidInputException if the value has an unexpected type
     */
    protected function remove(array &$array, string $key, array $types = ['string']): mixed
    {
        if (\array_key_exists($key, $array)) {
            $value = $array[$key];
            $actualType = \gettype($value);
            if ($actualType === 'string'
                && \in_array('numeric string', $types, true)
                && is_numeric($value)
                && trim($value) === $value
            ) {
                $actualType = 'numeric string';
            }
            if ($value !== null && !\in_array($actualType, $types, true)) {
                throw new InvalidInputException(
                    "Expected $key to be in [" . implode(', ', $types) . "] but was $actualType",
                );
            }
            unset($array[$key]);

            return $value;
        }

        return null;
    }

    /**
     * @param array<mixed> $values
     *
     * @throws InvalidInputException if the array has unknown keys and input
     *                               validation is enabled
     */
    protected function verifyEmpty(array $values): void
    {
        if (\count($values) !== 0) {
            $this->maybeThrowInvalidInputException('Unknown keys in array: ' . implode(', ', array_keys($values)));
        }
    }
}
