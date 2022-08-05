<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

use MaxMind\Exception\InvalidInputException;
use MaxMind\WebService\Client;
use Respect\Validation\Exceptions\ValidationException;

abstract class ServiceClient
{
    public const VERSION = 'v1.22.0';

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

    /**
     * @param string $className The name of the class (but not the namespace)
     * @param array  $values    The values to validate
     *
     * @throws InvalidInputException when $values does not validate
     *
     * @return array The cleaned values
     */
    protected function cleanAndValidate(string $className, array $values): array
    {
        $values = $this->clean($values);

        if (!$this->validateInput) {
            return $values;
        }

        $class = '\\MaxMind\\MinFraud\\Validation\\Rules\\' . $className;
        $validator = new $class();

        try {
            $validator->check($values);
        } catch (ValidationException $exception) {
            throw new InvalidInputException($exception->getMessage(), $exception->getCode());
        }

        return $values;
    }

    protected function clean(array $array): array
    {
        $cleaned = [];
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $cleaned[$key] = $this->clean($array[$key]);
            } elseif ($array[$key] !== null) {
                $cleaned[$key] = $array[$key];
            }
        }

        return $cleaned;
    }
}
