<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;

/**
 * @internal
 */
class Billing extends AbstractWrapper
{
    public function __construct()
    {
        $oneOf = call_user_func_array('Respect\Validation\Validator::oneOf', Address::keys());
        $this->validatable = Address::validator()
            ->each(null, $oneOf);
    }
}
