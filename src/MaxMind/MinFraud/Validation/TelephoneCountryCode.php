<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\Regex;

class TelephoneCountryCode extends Regex
{
    public function __construct($useLocale = false)
    {
        parent::__construct('/^[0-9]{1,4}$/');
    }
}
