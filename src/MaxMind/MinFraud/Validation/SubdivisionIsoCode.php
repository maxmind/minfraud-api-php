<?php

namespace MaxMind\MinFraud\Validation;

use Respect\Validation\Rules\Regex;

class SubdivisionIsoCode extends Regex
{
    public function __construct($useLocale = false)
    {
        parent::__construct('/^[0-9A-Z]{1,4}$/');
    }
}
