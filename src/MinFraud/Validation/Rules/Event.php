<?php

declare(strict_types=1);

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
        parent::__construct(v::keySet(
            v::key('shop_id', v::stringType(), false),
            v::key(
                'time',
                v::anyOf(
                    v::dateTime(\DateTime::RFC3339),
                    v::dateTime(\DateTime::RFC3339_EXTENDED),
                    // Respect/Validation no longer correctly supports the RFC 3339
                    // formats as of 2.3. See
                    // https://github.com/Respect/Validation/issues/1442.
                    v::dateTime('Y-m-d\TH:i:sp'),
                    v::dateTime('Y-m-d\TH:i:s.vp'),
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
        ));
    }
}
