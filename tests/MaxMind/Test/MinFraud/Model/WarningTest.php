<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Warning;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class WarningTest extends TestCase
{
    public function testWarning()
    {
        $array = [
            'code' => 'INVALID_INPUT',
            'warning' => 'Bad input',
            'input_pointer' => '/device/input',
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

        $this->assertSame(
            $array,
            $warning->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
