<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class IntOrString extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::oneOf(v::stringType(), v::intVal());
    }
}
