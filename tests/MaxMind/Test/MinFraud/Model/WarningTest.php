<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Warning;

class WarningTest extends \PHPUnit_Framework_TestCase
{
    public function testWarning()
    {
        $array = [
            'code' => 'INVALID_INPUT',
            'warning' => 'Bad input',
            'input' => ['device', 'input']
        ];
        $warning = new Warning($array);

        foreach ($array as $key => $value) {
            $this->assertEquals($value, $warning->$key, "$key is equal");

        }
    }
}
