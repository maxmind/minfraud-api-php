<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\IpAddress;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class IpAddressTest extends TestCase
{
    public function testIpAddress(): void
    {
        $array = [
            'risk' => 0.01,
            'risk_reasons' => [
                [
                    'code' => 'ANONYMOUS_IP',
                    'reason' => 'The IP address belongs to an anonymous network. See /ip_address/traits for more details.',
                ],
                [
                    'code' => 'MINFRAUD_NETWORK_ACTIVITY',
                    'reason' => 'Suspicious activity has been seen on this IP address across minFraud customers.',
                ],
            ],
            'country' => [
                'is_in_european_union' => true,
                'iso_code' => 'US',
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

        $this->assertSame(
            \count($array['risk_reasons']),
            \count($ipAddress->riskReasons),
            'correct number of risk reasons'
        );

        for ($i = 0; $i < 2; $i++) {
            $this->assertSame(
                $array['risk_reasons'][$i]['code'],
                $ipAddress->riskReasons[$i]->code,
                "risk reason $i has correct code"
            );

            $this->assertSame(
                $array['risk_reasons'][$i]['reason'],
                $ipAddress->riskReasons[$i]->reason,
                "risk reason $i has correct reason"
            );
        }

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

        $this->assertFalse(
            $ipAddress->registeredCountry->isInEuropeanUnion,
            'registered country is in European Union'
        );

        $this->assertFalse(
            $ipAddress->representedCountry->isInEuropeanUnion,
            'represented country is in European Union'
        );

        $this->assertEquals(
            $array,
            $ipAddress->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
