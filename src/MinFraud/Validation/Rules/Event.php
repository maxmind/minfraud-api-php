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
        $this->validatable = v::keySet(
            v::key('shop_id', v::stringType(), false),
            v::key(
                'time',
                v::oneOf(
                    v::date(\DateTime::RFC3339),
                    // This is the same as DateTime::RFC3339_EXTENDED,
                    // but PHP 5.6 doesn't have that.
                    v::date('Y-m-d\TH:i:s.vP')
                ),
                false
            ),
            v::key(
                'type',
                v::in(
                    [
                        'account_creation',
                        'account_login',
                        'email_change',
                        'password_reset',
                        'payout_change',
                        'purchase',
                        'recurring_purchase',
                        'referral',
                        'survey',
                    ]
                ),
                false
            ),
            v::key('transaction_id', v::stringType(), false)
        );
    }
}
