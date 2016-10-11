<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Device;

class DeviceTest extends \PHPUnit_Framework_TestCase
{
    public function testDevice()
    {
        $array = [
            'confidence' => 99,
            'id' => '915d5202-d6c5-4616-b2c1-87683975dadb',
            'last_seen' => '2016-06-08T14:16:38Z',
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
            $array,
            $device->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
