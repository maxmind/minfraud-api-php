<?php


namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class Order extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arr()
            ->key('affiliate_id', v::string(), false)
            ->key('amount', v::float()->min(0), false)
            ->key('currency', v::regex('/^[A-Z]{3}$/'), false)
            ->key('discount_code', v::string(), false)
            ->key('referrer_uri', v::url(), false)
            ->key('subaffiliate_id', v::string(), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('affiliate_id'),
                    v::equals('amount'),
                    v::equals('currency'),
                    v::equals('discount_code'),
                    v::equals('referrer_uri'),
                    v::equals('subaffiliate_id')
                )
            );
    }
}
