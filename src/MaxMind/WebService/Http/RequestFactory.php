<?php

namespace MaxMind\WebService\Http;


/**
 * This class is for internal use only. Semantic versioning does not not apply.
 * @package MaxMind\WebService
 */
class RequestFactory
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @param $url
     * @param $options
     * @return CurlRequest
     */
    public function request($url, $options)
    {
        return new CurlRequest($url, $options);
    }
}
