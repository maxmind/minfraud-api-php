<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    public function testEmail()
    {
        $array = [
            'is_free' => true,
            'is_high_risk' => true
        ];
        $email = new Email($array);

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
    }
}
