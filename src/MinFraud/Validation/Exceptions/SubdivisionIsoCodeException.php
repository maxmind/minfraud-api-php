<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @internal
 */
class SubdivisionIsoCodeException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be an ISO 3166-2 subdivision code',
        ],
    ];
}
