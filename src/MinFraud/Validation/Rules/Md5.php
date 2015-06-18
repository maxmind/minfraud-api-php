<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\Regex;

/**
 * @internal
 */
class Md5 extends Regex
{
    public function __construct()
    {
        parent::__construct('/^[0-9A-F]{32}$/i');
    }
}
