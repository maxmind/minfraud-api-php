<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\IpLocation;

class IpLocationTest extends \PHPUnit_Framework_TestCase
{
    public function testIpLocation()
    {
        $array = array(
            'country' => array(
                'iso_code' => 'US',
                'is_high_risk' => false,
            ),
            'location' => array(
                'local_time' => '2015-04-12T18:51:19-01:00',
                'accuracy_radius' => 5,
            ),
            'postal' => array(
                'code' => '31432'
            )
        );

        $ipLocation = new IpLocation($array);

        $this->assertEquals(
            $array['country']['iso_code'],
            $ipLocation->country->isoCode,
            'ISO code'
        );

        $this->assertEquals(
            $array['country']['is_high_risk'],
            $ipLocation->country->isHighRisk,
            'country is not high risk'
        );

        $this->assertEquals(
            $array['location']['local_time'],
            $ipLocation->location->localTime,
            'local time'
        );

        $this->assertEquals(
            $array['location']['accuracy_radius'],
            $ipLocation->location->accuracyRadius,
            'accuracy radius'
        );

        $this->assertEquals(
            $array['postal']['code'],
            $ipLocation->postal->code,
            'postal code'
        );
    }
}
