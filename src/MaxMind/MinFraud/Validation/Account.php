<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class Account extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arr()
            ->key('user_id', new IntOrString(), false)
            ->key('username_md5', new Md5(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('user_id'),
                    v::equals('username_md5')
                )
            );
    }
}
