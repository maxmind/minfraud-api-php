<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\Event;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class EventTest extends TestCase
{
    /**
     * @dataProvider eventTypeDataProvider
     *
     * @param mixed $good
     */
    public function testEventType($good)
    {
        $validator = new Event();

        $this->assertTrue(
            $validator->check(['type' => $good]),
            $good
        );
    }

    public function eventTypeDataProvider()
    {
        return [
            ['account_creation'],
            ['account_login'],
            ['email_change'],
            ['password_reset'],
            ['payout_change'],
            ['purchase'],
            ['recurring_purchase'],
            ['referral'],
            ['survey'],
        ];
    }
}
