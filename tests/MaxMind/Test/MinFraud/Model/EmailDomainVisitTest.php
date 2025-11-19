<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\EmailDomainVisit;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class EmailDomainVisitTest extends TestCase
{
    public function testEmailDomainVisitFull(): void
    {
        $array = [
            'has_redirect' => true,
            'last_visited_on' => '2025-11-15',
            'status' => 'live',
        ];
        $visit = new EmailDomainVisit($array);

        $this->assertTrue(
            $visit->hasRedirect,
            'hasRedirect'
        );

        $this->assertSame(
            $array['last_visited_on'],
            $visit->lastVisitedOn,
            'lastVisitedOn'
        );

        $this->assertSame(
            $array['status'],
            $visit->status,
            'status'
        );

        $this->assertSame(
            $array,
            $visit->jsonSerialize(),
            'jsonSerialize'
        );
    }

    public function testEmailDomainVisitEmpty(): void
    {
        $visit = new EmailDomainVisit(null);

        $this->assertNull($visit->hasRedirect, 'hasRedirect');
        $this->assertNull($visit->lastVisitedOn, 'lastVisitedOn');
        $this->assertNull($visit->status, 'status');

        $this->assertSame(
            [],
            $visit->jsonSerialize(),
            'jsonSerialize'
        );
    }

    public function testEmailDomainVisitPartial(): void
    {
        $array = [
            'last_visited_on' => '2025-11-15',
            'status' => 'parked',
        ];
        $visit = new EmailDomainVisit($array);

        $this->assertNull($visit->hasRedirect, 'hasRedirect is null when not present');

        $this->assertSame(
            $array['last_visited_on'],
            $visit->lastVisitedOn,
            'lastVisitedOn'
        );

        $this->assertSame(
            $array['status'],
            $visit->status,
            'status'
        );

        $this->assertSame(
            $array,
            $visit->jsonSerialize(),
            'jsonSerialize excludes null hasRedirect'
        );
    }

    public function testEmailDomainVisitStatusValues(): void
    {
        $statuses = ['live', 'dns_error', 'network_error', 'http_error', 'parked', 'pre_development'];

        foreach ($statuses as $status) {
            $visit = new EmailDomainVisit(['status' => $status]);
            $this->assertSame(
                $status,
                $visit->status,
                "status value: {$status}"
            );
        }
    }
}
