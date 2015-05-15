<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class IntOrString extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::oneOf(v::string(), v::int());
    }
}
