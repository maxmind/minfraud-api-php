<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\IpAddress;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class IpLocationTest extends TestCase
{
    public function testIpAddress()
    {
        $array = [
            'risk' => 0.01,
            'country' => [
                'is_in_european_union' => true,
                'iso_code' => 'US',
                'is_high_risk' => false,
            ],
            'location' => [
                'local_time' => '2015-04-12T18:51:19-01:00',
                'accuracy_radius' => 5,
            ],
            'postal' => [
                'code' => '31432',
            ],
        ];

        $ipAddress = new IpAddress($array);

        $this->assertSame(
            $array['risk'],
            $ipAddress->risk,
            'IP risk'
        );

        $this->assertTrue(
            $ipAddress->country->isInEuropeanUnion,
            'country is in European Union'
        );

        $this->assertSame(
            $array['country']['iso_code'],
            $ipAddress->country->isoCode,
            'ISO code'
        );

        $this->assertSame(
            $array['country']['is_high_risk'],
            $ipAddress->country->isHighRisk,
            'country is not high risk'
        );

        $this->assertSame(
            $array['location']['local_time'],
            $ipAddress->location->localTime,
            'local time'
        );

        $this->assertSame(
            $array['location']['accuracy_radius'],
            $ipAddress->location->accuracyRadius,
            'accuracy radius'
        );

        $this->assertSame(
            $array['postal']['code'],
            $ipAddress->postal->code,
            'postal code'
        );

        $this->assertFalse($ipAddress->registeredCountry->isInEuropeanUnion,
            'registered country is in European Union');

        $this->assertFalse($ipAddress->representedCountry->isInEuropeanUnion,
            'represented country is in European Union');

        $this->assertSame(
            $array,
            $ipAddress->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
