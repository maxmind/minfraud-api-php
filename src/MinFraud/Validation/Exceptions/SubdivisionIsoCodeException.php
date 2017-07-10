<?php

namespace MaxMind\MinFraud\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @internal
 */
class SubdivisionIsoCodeException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be an ISO 3166-2 subdivision code',
        ],
    ];
}
