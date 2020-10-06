<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\Device;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class DeviceTest extends TestCase
{
    public function testInvalidIP()
    {
        $validator = new Device();

        $this->expectException(\Respect\Validation\Exceptions\IpException::class);

        $validator->check([
           'ip_address' => '1.2.3',
        ]);
    }

    public function testMissingIP()
    {
        $validator = new Device();

        $this->assertTrue(
            $validator->check([
               'session_age' => 1.2,
            ])
        );
    }
}
