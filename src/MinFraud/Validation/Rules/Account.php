<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Account extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()
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
