<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class Email extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arr()
            ->key('address', v::oneOf(v::email(), new Md5()), false)
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
