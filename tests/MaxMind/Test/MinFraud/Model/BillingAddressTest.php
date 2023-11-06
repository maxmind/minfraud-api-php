<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\BillingAddress;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class BillingAddressTest extends TestCase
{
    public function testBillingAddress(): void
    {
        $array = [
            'is_postal_in_city' => true,
            'latitude' => 12.3,
            'longitude' => 132.0,
            'distance_to_ip_location' => 240,
            'is_in_ip_country' => false,
        ];
        $billing = new BillingAddress($array);

        $this->assertSame(
            $array['is_postal_in_city'],
            $billing->isPostalInCity,
            'isPostalInCity'
        );

        $this->assertSame(
            $array['latitude'],
            $billing->latitude,
            'latitude'
        );

        $this->assertSame(
            $array['longitude'],
            $billing->longitude,
            'longitude'
        );

        $this->assertSame(
            $array['distance_to_ip_location'],
            $billing->distanceToIpLocation,
            'distanceToIpLocation'
        );

        $this->assertSame(
            $array['is_in_ip_country'],
            $billing->isInIpCountry,
            'isInIpCountry'
        );

        $this->assertEquals(
            $array,
            $billing->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
