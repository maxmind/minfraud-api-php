<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class CustomInputs extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(
            v::allOf(
                v::arrayVal()->each(
                    v::anyOf(
                        v::stringType()->not(v::contains("\n"))->length(1, 255),
                        v::numericVal()->max(1e13 - 1)->min(-1e13 + 1),
                        v::boolType()
                    ),
                ),
                v::call('array_keys', v::each(v::regex('/^[a-z0-9_]{1,25}\Z/'))),
            ),
        );
    }
}
