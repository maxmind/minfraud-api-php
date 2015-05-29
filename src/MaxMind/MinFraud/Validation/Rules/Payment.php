<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Payment extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arr()
            ->key('processor', new PaymentProcessor(), false)
            ->key('was_authorized', v::bool(), false)
            ->key('decline_code', v::string(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('processor'),
                    v::equals('was_authorized'),
                    v::equals('decline_code')
                )
            );
    }
}
