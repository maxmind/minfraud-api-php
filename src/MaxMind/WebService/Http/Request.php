<?php

namespace MaxMind\WebService\Http;

interface Request
{
    public function __construct($url, $options);
    public function post($body);
}
