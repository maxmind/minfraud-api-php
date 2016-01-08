<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class ShoppingCartItem extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()
            ->key('category', v::stringType(), false)
            ->key('item_id', new IntOrString(), false)
            ->key('price', v::floatVal()->min(0, false), false)
            ->key('quantity', v::intVal()->min(0, false), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('category'),
                    v::equals('item_id'),
                    v::equals('price'),
                    v::equals('quantity')
                )
            );
    }
}
