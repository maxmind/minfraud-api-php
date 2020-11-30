<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class IntOrString extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(v::anyOf(v::stringType(), v::intVal()));
    }
}
