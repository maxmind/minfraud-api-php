<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Score;
use MaxMind\Test\MinFraudData as Data;

class ScoreTest extends \PHPUnit_Framework_TestCase
{
    public function testScore()
    {
        $array = Data::scoreFullResponse();

        $score = new Score($array);

        $this->assertEquals(
            $array['id'],
            $score->id,
            'id'
        );

        $this->assertEquals(
            $array['credits_remaining'],
            $score->creditsRemaining,
            'credits remaining'
        );

        $this->assertEquals(
            $array['risk_score'],
            $score->riskScore,
            'credits remaining'
        );

        $this->assertEquals(
            count($array['warnings']),
            count($score->warnings),
            'correct number of warnings'
        );

        $this->assertEquals(
            $array['warnings'][0]['code'],
            $score->warnings[0]->code,
            'first warning has correct code'
        );

        $this->assertEquals(
            $array,
            $score->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
