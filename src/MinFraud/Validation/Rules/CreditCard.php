<?php

declare(strict_types=1);

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
        parent::__construct(
            v::keySet(
                v::key('avs_result', v::stringType()->length(1, 1), false),
                v::key('bank_name', v::stringType(), false),
                v::key('bank_phone_country_code', new TelephoneCountryCode(), false),
                v::key('bank_phone_number', v::stringType(), false),
                v::key('country', v::countryCode(), false),
                v::key('cvv_result', v::stringType()->length(1, 1), false),
                v::key('issuer_id_number', v::regex('/^(?:[0-9]{6}|[0-9]{8})$/'), false),
                v::key('last_digits', v::regex('/^(?:[0-9]{2}|[0-9]{4})$/'), false),
                v::key('token', v::regex('/^[\x21-\x7E]{1,255}$/')->not(v::regex('/^[0-9]{1,19}$/')), false),
                v::key('was_3d_secure_successful', v::boolVal(), false),
            )
        );
    }
}
