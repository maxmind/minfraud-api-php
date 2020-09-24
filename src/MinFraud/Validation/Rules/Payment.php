<?php

declare(strict_types=1);

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
        parent::__construct(v::keySet(
            v::key('processor', new PaymentProcessor(), false),
            v::key('was_authorized', v::boolVal(), false),
            v::key('decline_code', v::stringType(), false)
        ));
    }
}
