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
        $this->validatable = v::arr()
            ->key('accept_language', v::string(), false)
            ->key('ip_address', v::ip(), true)
            ->key('user_agent', v::string(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('accept_language'),
                    v::equals('ip_address'),
                    v::equals('user_agent')
                )
            );
    }
}
