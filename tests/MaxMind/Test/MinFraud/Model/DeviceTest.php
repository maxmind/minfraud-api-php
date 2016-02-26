<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Device;

class DeviceTest extends \PHPUnit_Framework_TestCase
{
    public function testDevice()
    {
        $array = [
            'id' => '915d5202-d6c5-4616-b2c1-87683975dadb'
        ];
        $device = new Device($array);

        $this->assertEquals(
            $array['id'],
            $device->id,
            'id'
        );

        $this->assertEquals(
            $array,
            $device->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
