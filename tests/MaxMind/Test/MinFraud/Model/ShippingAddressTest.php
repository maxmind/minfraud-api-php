<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\ShippingAddress;

class ShippingAddressTest extends \PHPUnit_Framework_TestCase
{
    public function testShippingAddress()
    {
        $array = [
            'is_postal_in_city' => true,
            'latitude' => 12.3,
            'longitude' => 132,
            'distance_to_ip_location' => 240,
            'is_in_ip_country' => false,
            'is_high_risk' => true,
            'distance_to_billing_address' => 20,
        ];
        $shipping = new ShippingAddress($array);

        $this->assertSame(
            $array['is_high_risk'],
            $shipping->isHighRisk,
            'is high risk'
        );

        $this->assertSame(
            $array['distance_to_billing_address'],
            $shipping->distanceToBillingAddress,
            'distance to billing address is correct'
        );

        $this->assertSame(
            $array['is_postal_in_city'],
            $shipping->isPostalInCity,
            'isPostalInCity'
        );

        $this->assertSame(
            $array['latitude'],
            $shipping->latitude,
            'latitude'
        );

        $this->assertSame(
            $array['longitude'],
            $shipping->longitude,
            'longitude'
        );

        $this->assertSame(
            $array['distance_to_ip_location'],
            $shipping->distanceToIpLocation,
            'distanceToIpLocation'
        );

        $this->assertSame(
            $array['is_in_ip_country'],
            $shipping->isInIpCountry,
            'isInIpCountry'
        );

        $this->assertSame(
            $array,
            $shipping->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
