<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\Event;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class EventTest extends TestCase
{
    /**
     * @dataProvider eventTypeDataProvider
     */
    public function testEventType(string $good): void
    {
        $validator = new Event();

        $this->assertTrue(
            $validator->validate(['type' => $good]),
            $good
        );
    }

    public static function eventTypeDataProvider(): array
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
