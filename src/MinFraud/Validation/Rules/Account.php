<?php

declare(strict_types=1);

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
        parent::__construct(v::keySet(
            v::key('user_id', new IntOrString(), false),
            v::key('username_md5', new Md5(), false)
        ));
    }
}
