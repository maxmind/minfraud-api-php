<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Email;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class EmailTest extends TestCase
{
    public function testEmail(): void
    {
        $array = [
            'domain' => [
                'first_seen' => '2014-02-03',
            ],
            'first_seen' => '2017-01-02',
            'is_disposable' => true,
            'is_free' => true,
            'is_high_risk' => true,
        ];
        $email = new Email($array);

        $this->assertSame(
            $array['domain']['first_seen'],
            $email->domain->firstSeen,
            'domain->firstSeen'
        );

        $this->assertSame(
            $array['first_seen'],
            $email->firstSeen,
            'firstSeen'
        );

        $this->assertSame(
            $array['is_disposable'],
            $email->isDisposable,
            'isDisposable'
        );

        $this->assertSame(
            $array['is_free'],
            $email->isFree,
            'isFree'
        );

        $this->assertSame(
            $array['is_high_risk'],
            $email->isHighRisk,
            'isHighRisk'
        );

        $this->assertSame(
            $array,
            $email->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
