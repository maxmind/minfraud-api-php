<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;

/**
 * @internal
 */
class Billing extends AbstractWrapper
{
    use Address;

    public function __construct()
    {
        $this->validatable = call_user_func_array('Respect\Validation\Validator::keySet', Address::keys());
    }
}
