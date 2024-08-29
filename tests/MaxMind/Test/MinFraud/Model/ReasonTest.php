<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Reason;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class ReasonTest extends TestCase
{
    public function testReason(): void
    {
        $array = [
            'code' => 'ANONYMOUS_IP',
            'reason' => 'Risk due to IP being an Anonymous IP',
        ];
        $reason = new Reason($array);

        $this->assertSame(
            $array['code'],
            $reason->code,
            'code'
        );

        $this->assertSame(
            $array['reason'],
            $reason->reason,
            'reason'
        );

        $this->assertSame(
            $array,
            $reason->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
