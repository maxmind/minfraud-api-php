<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class CreditCard extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arrayVal()
            ->key('avs_result', v::stringType()->length(1, 1), false)
            ->key('bank_name', v::stringType(), false)
            ->key('bank_phone_country_code', new TelephoneCountryCode(), false)
            ->key('bank_phone_number', v::stringType(), false)
            ->key('cvv_result', v::stringType()->length(1, 1), false)
            ->key('issuer_id_number', v::regex('/^[0-9]{6}$/'), false)
            ->key('last_4_digits', v::regex('/^[0-9]{4}$/'), false)
            ->each(
                null,
                v::oneOf(
                    v::equals('avs_result'),
                    v::equals('bank_name'),
                    v::equals('bank_phone_country_code'),
                    v::equals('bank_phone_number'),
                    v::equals('cvv_result'),
                    v::equals('issuer_id_number'),
                    v::equals('last_4_digits')
                )
            );
    }
}
