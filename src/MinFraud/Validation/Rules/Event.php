<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Event extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()
            ->key('shop_id', v::stringType(), false)
            ->key('time', v::date(\DateTime::RFC3339), false)
            ->key(
                'type',
                v::in(
                    array(
                        'account_creation',
                        'account_login',
                        'purchase',
                        'recurring_purchase',
                        'referral',
                        'survey',
                    )
                ),
                false
            )
            ->key('transaction_id', v::stringType(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('shop_id'),
                    v::equals('time'),
                    v::equals('type'),
                    v::equals('transaction_id')
                )
            );
    }
}
