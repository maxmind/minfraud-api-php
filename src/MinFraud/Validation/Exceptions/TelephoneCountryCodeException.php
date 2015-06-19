<?php

namespace MaxMind\MinFraud\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @internal
 */
class TelephoneCountryCodeException extends ValidationException
{
    public static $defaultTemplates = array(
        self::MODE_DEFAULT => array(
            self::STANDARD => '{{name}} must be a valid telephone country code',
        )
    );
}
