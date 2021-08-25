<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Disposition;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class DispositionTest extends TestCase
{
    public function testDisposition(): void
    {
        $array = [
            'action' => 'manual_review',
            'reason' => 'custom_rule',
            'rule_label' => 'custom rule label',
        ];
        $disposition = new Disposition($array);

        $this->assertSame(
            $array['action'],
            $disposition->action,
            'action'
        );

        $this->assertSame(
            $array['reason'],
            $disposition->reason,
            'reason'
        );

        $this->assertSame(
            $array['rule_label'],
            $disposition->ruleLabel,
            'ruleLabel'
        );

        $this->assertSame(
            $array,
            $disposition->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
