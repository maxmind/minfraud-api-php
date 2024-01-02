<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\Device;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\IpException;

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

        $this->expectException(IpException::class);

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
