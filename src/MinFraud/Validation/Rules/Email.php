<?php

declare(strict_types=1);

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
        parent::__construct(v::keySet(
            v::key('address', v::oneOf(new Md5(), v::email()), false),
            v::key('domain', v::domain(false), false)
        ));
    }
}
