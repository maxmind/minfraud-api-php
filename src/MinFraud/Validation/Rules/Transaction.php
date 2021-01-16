<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Transaction extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(
            v::allOf(
                v::keySet(
                    v::key('account', new Account(), false),
                    v::key('billing', new Billing(), false),
                    v::key('credit_card', new CreditCard(), false),
                    v::key('custom_inputs', new CustomInputs(), false),
                    v::key('device', new Device(), false),
                    v::key('email', new Email(), false),
                    v::key('event', new Event(), false),
                    v::key('order', new Order(), false),
                    v::key('payment', new Payment(), false),
                    v::key('shipping', new Shipping(), false),
                    v::key('shopping_cart', v::arrayVal()->each(new ShoppingCartItem()), false)
                ),
                v::arrayVal()->length(1, null),
            ),
        );
    }
}
