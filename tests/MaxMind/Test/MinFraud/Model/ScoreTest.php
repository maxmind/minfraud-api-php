<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Score;
use MaxMind\Test\MinFraudData as Data;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class ScoreTest extends TestCase
{
    protected function response(): array
    {
        return Data::scoreFullResponse();
    }

    /**
     * This is specified here as PHP 7.3 doesn't allow us to set it to
     * a subclass of Score in subclasses of this test class.
     *
     * @return Score
     */
    protected function model()
    {
        return new Score($this->response());
    }

    public function testScoreProperties(): void
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
