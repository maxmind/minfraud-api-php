<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Disposition;

class DispositionTest extends \PHPUnit_Framework_TestCase
{
    public function testDisposition()
    {
        $array = [
            'action' => 'manual_review',
            'reason' => 'custom_rule',
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
            $array,
            $disposition->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
