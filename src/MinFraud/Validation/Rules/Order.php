<?php

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
        $this->validatable = v::arrayVal()
            ->key('affiliate_id', v::stringType(), false)
            ->key('amount', v::floatVal()->min(0, false), false)
            ->key('currency', v::regex('/^[A-Z]{3}$/'), false)
            ->key('discount_code', v::stringType(), false)
            ->key('has_gift_message', v::boolVal(), false)
            ->key('is_gift', v::boolVal(), false)
            ->key('referrer_uri', v::url(), false)
            ->key('subaffiliate_id', v::stringType(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('affiliate_id'),
                    v::equals('amount'),
                    v::equals('currency'),
                    v::equals('discount_code'),
                    v::equals('has_gift_message'),
                    v::equals('is_gift'),
                    v::equals('referrer_uri'),
                    v::equals('subaffiliate_id')
                )
            );
    }
}
