<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Score;
use MaxMind\Test\MinFraudData as Data;

class ScoreTest extends \PHPUnit_Framework_TestCase
{
    protected function response()
    {
        return Data::scoreFullResponse();
    }

    protected function model()
    {
        return new Score($this->response());
    }

    public function testScoreProperties()
    {
        $array = $this->response();
        $score = $this->model();

        $this->assertEquals(
            $array['id'],
            $score->id,
            'id'
        );

        $this->assertEquals(
            $array['funds_remaining'],
            $score->fundsRemaining,
            'funds remaining'
        );

        $this->assertEquals(
            $array['queries_remaining'],
            $score->queriesRemaining,
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
