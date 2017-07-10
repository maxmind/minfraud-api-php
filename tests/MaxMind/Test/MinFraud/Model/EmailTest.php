<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Email;

/**
 * @coversNothing
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testEmail()
    {
        $array = [
            'first_seen' => '2017-01-02',
            'is_free' => true,
            'is_high_risk' => true,
        ];
        $email = new Email($array);

        $this->assertSame(
            $array['first_seen'],
            $email->firstSeen,
            'firstSeen'
        );

        $this->assertSame(
            $array['is_free'],
            $email->isFree,
            'isFree'
        );

        $this->assertSame(
            $array['is_high_risk'],
            $email->isHighRisk,
            'isHighRisk'
        );

        $this->assertSame(
            $array,
            $email->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
