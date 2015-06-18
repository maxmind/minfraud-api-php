<?php

namespace MaxMind\MinFraud\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

/**
 * @internal
 */
class Md5Exception extends ValidationException
{
    public static $defaultTemplates = array(
        self::MODE_DEFAULT => array(
            self::STANDARD => '{{name}} must be an MD5',
        )
    );
}
