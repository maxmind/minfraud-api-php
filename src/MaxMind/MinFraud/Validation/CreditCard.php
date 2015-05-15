<?php


namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

class CreditCard extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::arr()
            ->key('avs_result', v::string()->length(1, 1), false)
            ->key('bank_name', v::string(), false)
            ->key('bank_phone_country_code', new TelephoneCountryCode(), false)
            ->key('bank_phone_number', v::string())
            ->key('cvv_result', v::string()->length(1, 1), false)
            ->key('issuer_id_number', v::regex('/^[0-9]{6}$/'))
            ->key('last_4_digits', v::regex('/^[0-9]{4}$/'))
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
