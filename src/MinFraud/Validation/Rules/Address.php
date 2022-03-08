<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Address
{
    public static function keys(): array
    {
        return [
            v::key('address', new IntOrString(), false),
            v::key('address_2', new IntOrString(), false),
            v::key('city', v::stringType(), false),
            v::key('company', v::stringType(), false),
            v::key('country', v::countryCode(), false),
            v::key('first_name', v::stringType(), false),
            v::key('last_name', v::stringType(), false),
            v::key('phone_country_code', new TelephoneCountryCode(), false),
            v::key('phone_number', v::stringType(), false),
            v::key('postal', v::stringType(), false),
            v::key('region', new SubdivisionIsoCode(), false),
        ];
    }
}
