<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\Regex;

/**
 * @internal
 */
class TelephoneCountryCode extends Regex
{
    public function __construct()
    {
        parent::__construct('/^[0-9]{1,4}$/');
    }
}
