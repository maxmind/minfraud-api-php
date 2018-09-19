<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Score;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 */
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

        $this->assertSame(
            $array['id'],
            $score->id,
            'id'
        );

        $this->assertSame(
            $array['funds_remaining'],
            $score->fundsRemaining,
            'funds remaining'
        );

        $this->assertSame(
            $array['queries_remaining'],
            $score->queriesRemaining,
            'credits remaining'
        );

        $this->assertSame(
            $array['risk_score'],
            $score->riskScore,
            'credits remaining'
        );

        $this->assertSame(
            $array['disposition']['action'],
            $score->disposition->action,
            'disposition action'
        );

        $this->assertSame(
            $array['ip_address']['risk'],
            $score->ipAddress->risk,
            'IP address risk'
        );

        $this->assertSame(
            \count($array['warnings']),
            \count($score->warnings),
            'correct number of warnings'
        );

        $this->assertSame(
            $array['warnings'][0]['code'],
            $score->warnings[0]->code,
            'first warning has correct code'
        );

        $this->assertSame(
            $array,
            $score->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
