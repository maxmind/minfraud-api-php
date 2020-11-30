<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @internal
 */
class TelephoneCountryCodeException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be a valid telephone country code',
        ],
    ];
}
