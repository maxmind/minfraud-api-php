<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Phone;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class PhoneTest extends TestCase
{
    public function testPhone(): void
    {
        $array = [
            'country' => 'US',
            'is_voip' => true,
            'network_operator' => 'Verizon/1',
            'number_type' => 'fixed',
        ];
        $phone = new Phone($array);

        $this->assertSame(
            $array['country'],
            $phone->country,
            'country'
        );

        $this->assertSame(
            $array['is_voip'],
            $phone->isVoip,
            'isVoip'
        );

        $this->assertSame(
            $array['network_operator'],
            $phone->networkOperator,
            'networkOperator'
        );

        $this->assertSame(
            $array['number_type'],
            $phone->numberType,
            'numberType'
        );

        $this->assertSame(
            $array,
            $phone->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
