<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\RiskScoreReason;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class RiskScoreReasonTest extends TestCase
{
    public function testRiskScoreReason(): void
    {
        $array = [
            'multiplier' => 45.0,
            'reasons' => [
                [
                    'code' => 'ANONYMOUS_IP',
                    'reason' => 'Risk due to IP being an Anonymous IP',
                ],
            ],
        ];

        $reason = new RiskScoreReason($array);

        $this->assertSame(
            $array['multiplier'],
            $reason->multiplier,
            'multiplier'
        );

        $this->assertSame(
            \count($array['reasons']),
            \count($reason->reasons),
            'correct number of reasons'
        );

        $this->assertSame(
            $array['reasons'][0]['code'],
            $reason->reasons[0]->code,
            'correct code'
        );

        $this->assertSame(
            $array['reasons'][0]['reason'],
            $reason->reasons[0]->reason,
            'correct reason'
        );
    }
}
