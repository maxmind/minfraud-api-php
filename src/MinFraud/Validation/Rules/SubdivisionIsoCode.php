<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractEnvelope;
use Respect\Validation\Rules\Regex;

/**
 * @internal
 */
class SubdivisionIsoCode extends AbstractEnvelope
{
    public function __construct()
    {
        parent::__construct(new Regex('/^[0-9A-Z]{1,4}$/'));
    }
}
