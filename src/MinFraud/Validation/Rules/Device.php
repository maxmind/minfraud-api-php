<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Device extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::keySet(
            v::key('accept_language', v::stringType(), false),
            v::key('ip_address', v::ip(), true),
            v::key('user_agent', v::stringType(), false)
        );
    }
}
