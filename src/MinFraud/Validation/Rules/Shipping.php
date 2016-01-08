<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Shipping extends AbstractWrapper
{
    use Address;

    public function __construct()
    {
        $keys = Address::keys();
        array_push(
            $keys,
            v::key(
                'delivery_speed',
                v::in(['same_day', 'overnight', 'expedited', 'standard']),
                false
            )
        );
        $this->validatable = call_user_func_array('Respect\Validation\Validator::keySet', $keys);
    }
}
