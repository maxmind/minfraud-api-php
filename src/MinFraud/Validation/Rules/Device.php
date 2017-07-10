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
            v::key('session_id', v::oneOf(v::stringType(), v::intVal())->length(1, 255), false),
            v::key('session_age', v::floatVal()->min(0, true), false),
            v::key('user_agent', v::stringType(), false)
        );
    }
}
