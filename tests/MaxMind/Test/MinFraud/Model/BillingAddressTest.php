<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\BillingAddress;

class BillingAddressTest extends \PHPUnit_Framework_TestCase
{
    public function testBillingAddress()
    {
        $array = [
            'is_postal_in_city' => true,
            'latitude' => 12.3,
            'longitude' => 132,
            'distance_to_ip_location' => 240,
            'is_in_ip_country' => false,
        ];
        $billing = new BillingAddress($array);

        $this->assertEquals(
            $array['is_postal_in_city'],
            $billing->isPostalInCity,
            'isPostalInCity'
        );

        $this->assertEquals(
            $array['latitude'],
            $billing->latitude,
            'latitude'
        );

        $this->assertEquals(
            $array['longitude'],
            $billing->longitude,
            'longitude'
        );

        $this->assertEquals(
            $array['distance_to_ip_location'],
            $billing->distanceToIpLocation,
            'distanceToIpLocation'
        );

        $this->assertEquals(
            $array['is_in_ip_country'],
            $billing->isInIpCountry,
            'isInIpCountry'
        );
    }
}
