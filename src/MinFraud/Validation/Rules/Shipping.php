<?php

declare(strict_types=1);

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
        $keys = Address::keys();

        $keys[] =
            v::key(
                'delivery_speed',
                v::in(['same_day', 'overnight', 'expedited', 'standard']),
                false
            )
        ;
        parent::__construct(\call_user_func_array('Respect\Validation\Validator::keySet', $keys));
    }
}
