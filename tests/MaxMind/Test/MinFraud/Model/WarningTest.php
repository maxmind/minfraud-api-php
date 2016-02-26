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
            'input_pointer' => '/device/input'
        ];
        $warning = new Warning($array);

        $this->assertSame(
            $array['code'],
            $warning->code,
            'code'
        );

        $this->assertSame(
            $array['warning'],
            $warning->warning,
            'warning'
        );

        $this->assertSame(
            $array['input_pointer'],
            $warning->inputPointer,
            'inputPointer'
        );

        $this->assertEquals(
            $array,
            $warning->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
