<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class CustomInputs extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()->each(
            v::oneOf(
                v::stringType()->not(v::contains("\n"))->length(1, 255),
                v::numeric()->max((1 << 53) - 1)->min(-(1 << 53) + 1),
                v::boolType()
            ),
            v::regex('/^[a-z0-9_]{1,25}\Z/')
        );
    }
}
