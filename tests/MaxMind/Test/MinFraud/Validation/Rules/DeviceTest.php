<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\Device;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class DeviceTest extends TestCase
{
    public function testInvalidIP(): void
    {
        $validator = new Device();

        $this->expectException(\Respect\Validation\Exceptions\IpException::class);

        $validator->check([
            'ip_address' => '1.2.3',
        ]);
    }

    public function testMissingIP(): void
    {
        $validator = new Device();

        $this->assertTrue(
            $validator->validate([
                'session_age' => 1.2,
            ])
        );
    }
}
