<?php

namespace MaxMind\Test;

use MaxMind\MinFraud\Validation\Rules\Event;

/**
 * @coversNothing
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testEventType()
    {
        $validator = new Event();

        $good = [
            'account_creation',
            'account_login',
            'email_change',
            'password_reset',
            'payout_change',
            'purchase',
            'recurring_purchase',
            'referral',
            'survey',
        ];

        foreach ($good as $value) {
            $this->assertTrue(
                $validator->check(['type' => $value]),
                $value
            );
        }
    }
}
