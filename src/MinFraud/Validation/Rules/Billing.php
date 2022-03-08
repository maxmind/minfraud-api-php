<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;

/**
 * @internal
 */
class Billing extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(\call_user_func_array('Respect\Validation\Validator::keySet', Address::keys()));
    }
}
