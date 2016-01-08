<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Email extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()
            ->key('address', v::oneOf(new Md5(), v::email()), false)
            ->key('domain', v::domain(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('address'),
                    v::equals('domain')
                )
            );
    }
}
