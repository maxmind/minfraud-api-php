<?php

namespace MaxMind\MinFraud;

use MaxMind\Exception\InvalidInputException;
use MaxMind\WebService\Client;
use Respect\Validation\Exceptions\ValidationException;

abstract class ServiceClient
{
    const VERSION = 'v1.14.0';

    protected $client;
    protected static $host = 'minfraud.maxmind.com';
    protected static $basePath = '/minfraud/v2.0/';
    protected $validateInput = true;

    public function __construct(
        $accountId,
        $licenseKey,
        $options = []
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
    protected function userAgent()
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
    protected function cleanAndValidate($className, $values)
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

    protected function clean($array)
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
