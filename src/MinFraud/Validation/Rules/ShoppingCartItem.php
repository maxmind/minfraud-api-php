<?php

declare(strict_types=1);

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
        parent::__construct(v::keySet(
            v::key('category', v::stringType(), false),
            v::key('item_id', new IntOrString(), false),
            v::key('price', v::floatVal()->min(0), false),
            v::key('quantity', v::intVal()->greaterThan(0), false)
        ));
    }
}
