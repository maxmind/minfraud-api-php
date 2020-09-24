<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Device;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class DeviceTest extends TestCase
{
    public function testDevice()
    {
        $array = [
            'confidence' => 99,
            'id' => '915d5202-d6c5-4616-b2c1-87683975dadb',
            'last_seen' => '2016-06-08T14:16:38Z',
            'local_time' => '2016-06-10T14:19:10-08:00',
        ];
        $device = new Device($array);

        $this->assertSame(
            $array['confidence'],
            $device->confidence,
            'confidence'
        );

        $this->assertSame(
            $array['id'],
            $device->id,
            'id'
        );

        $this->assertSame(
            $array['last_seen'],
            $device->lastSeen,
            'last_seen'
        );

        $this->assertSame(
            $array['local_time'],
            $device->localTime,
            'local_time'
        );

        $this->assertSame(
            $array,
            $device->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
