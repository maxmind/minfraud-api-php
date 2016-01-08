<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Validator as v;

/**
 * @internal
 */
class Address
{
    public static function validator()
    {
        return v::arrayVal()
            ->key('address', new IntOrString(), false)
            ->key('address_2', new IntOrString(), false)
            ->key('city', v::stringType(), false)
            ->key('company', v::stringType(), false)
            ->key('country', v::countryCode(), false)
            ->key('first_name', v::stringType(), false)
            ->key('last_name', v::stringType(), false)
            ->key('phone_country_code', new TelephoneCountryCode(), false)
            ->key('phone_number', v::stringType(), false)
            ->key('postal', v::stringType(), false)
            ->key('region', new SubdivisionIsoCode(), false);
    }

    public static function keys()
    {
        return array(
            v::equals('address'),
            v::equals('address_2'),
            v::equals('city'),
            v::equals('company'),
            v::equals('country'),
            v::equals('first_name'),
            v::equals('last_name'),
            v::equals('phone_country_code'),
            v::equals('phone_number'),
            v::equals('postal'),
            v::equals('region')
        );
    }
}
