<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

use MaxMind\Exception\InvalidInputException;
use MaxMind\WebService\Client;

abstract class ServiceClient
{
    public const VERSION = 'v3.0.0';

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

    protected function maybeThrowInvalidInputException(string $msg): void
    {
        if ($this->validateInput) {
            throw new InvalidInputException($msg);
        }
    }

    protected function remove(array &$array, string $key, array $types = ['string']): mixed
    {
        if (\array_key_exists($key, $array)) {
            $value = $array[$key];
            $actualType = \gettype($value);
            if ($value !== null && !\in_array($actualType, $types, true)) {
                $this->maybeThrowInvalidInputException(
                    "Expected $key to be in [" . implode(', ', $types) . "] but was $actualType",
                );
            }
            unset($array[$key]);

            return $value;
        }

        return null;
    }

    protected function verifyEmpty(array $values): void
    {
        if (\count($values) !== 0) {
            $this->maybeThrowInvalidInputException('Unknown keys in array: ' . implode(', ', array_keys($values)));
        }
    }
}
