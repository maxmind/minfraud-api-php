<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class TransactionReport extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(v::keySet(
            v::key('chargeback_code', v::stringType(), false),
            v::key('ip_address', v::ip(), true),
            v::key('maxmind_id', v::stringType()->length(8, 8), false),
            v::key(
                'minfraud_id',
                v::regex('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i'),
                false
            ),
            v::key('notes', v::stringType(), false),
            v::key(
                'tag',
                v::in(
                    [
                        'not_fraud',
                        'suspected_fraud',
                        'spam_or_abuse',
                        'chargeback',
                    ]
                ),
                true
            ),
            v::key('transaction_id', v::stringType(), false)
        ));
    }
}
