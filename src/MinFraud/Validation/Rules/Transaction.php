<?php

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
        $this->validatable = v::arr()
            ->key('account', new Account(), false)
            ->key('billing', new Billing(), false)
            ->key('payment', new Payment(), false)
            ->key('credit_card', new CreditCard(), false)
            ->key('device', new Device(), true)
            ->key('email', new Email(), false)
            ->key('event', new Event(), false)
            ->key('order', new Order(), false)
            ->key('shipping', new Shipping(), false)
            ->key('shopping_cart', v::arr()->each(new ShoppingCartItem()))
            ->each(
                null,
                v::oneOf(
                    v::equals('account'),
                    v::equals('billing'),
                    v::equals('payment'),
                    v::equals('credit_card'),
                    v::equals('device'),
                    v::equals('email'),
                    v::equals('event'),
                    v::equals('order'),
                    v::equals('shipping'),
                    v::equals('shopping_cart')
                )
            );
    }
}
