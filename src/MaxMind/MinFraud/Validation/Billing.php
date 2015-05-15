<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class Billing extends AbstractWrapper
{
    public function __construct()
    {
        $oneOf = call_user_func_array('Respect\Validation\Validator::oneOf', Address::keys());
        $this->validatable = Address::validator()
            ->each(null, $oneOf);
    }
}
