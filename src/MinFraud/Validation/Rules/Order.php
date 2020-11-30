<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Order extends AbstractWrapper
{
    public function __construct()
    {
        parent::__construct(v::keySet(
            v::key('affiliate_id', v::stringType(), false),
            v::key('amount', v::floatVal()->min(0), false),
            v::key('currency', v::regex('/^[A-Z]{3}$/'), false),
            v::key('discount_code', v::stringType(), false),
            v::key('has_gift_message', v::boolVal(), false),
            v::key('is_gift', v::boolVal(), false),
            v::key('referrer_uri', v::url(), false),
            v::key('subaffiliate_id', v::stringType(), false)
        ));
    }
}
