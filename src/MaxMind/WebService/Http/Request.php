<?php

namespace MaxMind\WebService\Http;

/**
 * Interface Request
 * @package MaxMind\WebService\Http
 */
interface Request
{
    /**
     * @param $url
     * @param $options
     */
    public function __construct($url, $options);

    /**
     * @param $body
     * @return mixed
     */
    public function post($body);
}
