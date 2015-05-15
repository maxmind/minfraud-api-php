<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Shipping extends AbstractWrapper
{
    public function __construct()
    {
        $keys =  Address::keys();
        array_push($keys, v::equals('delivery_speed'));
        $oneOf = call_user_func_array('\Respect\Validation\Validator::oneOf', $keys);
        $this->validatable = Address::validator()
            ->key(
                'delivery_speed',
                v::in(array('same_day', 'overnight', 'expedited', 'standard')),
                false
            )
            ->each(null, $oneOf);
    }
}
