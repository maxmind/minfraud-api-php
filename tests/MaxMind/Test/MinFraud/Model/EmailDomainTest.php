<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\EmailDomain;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class EmailDomainTest extends TestCase
{
    public function testEmailDomainFull(): void
    {
        $array = [
            'classification' => 'business',
            'first_seen' => '2017-01-02',
            'risk' => 1.23,
            'visit' => [
                'has_redirect' => true,
                'last_visited_on' => '2025-11-15',
                'status' => 'live',
            ],
            'volume' => 6.5,
        ];
        $email = new EmailDomain($array);

        $this->assertSame(
            $array['classification'],
            $email->classification,
            'classification'
        );

        $this->assertSame(
            $array['first_seen'],
            $email->firstSeen,
            'firstSeen'
        );

        $this->assertSame(
            $array['risk'],
            $email->risk,
            'risk'
        );

        $this->assertTrue(
            $email->visit->hasRedirect,
            'visit->hasRedirect'
        );

        $this->assertSame(
            $array['visit']['last_visited_on'],
            $email->visit->lastVisitedOn,
            'visit->lastVisitedOn'
        );

        $this->assertSame(
            $array['visit']['status'],
            $email->visit->status,
            'visit->status'
        );

        $this->assertSame(
            $array['volume'],
            $email->volume,
            'volume'
        );

        $this->assertSame(
            $array,
            $email->jsonSerialize(),
            'jsonSerialize'
        );
    }

    public function testEmailDomainEmpty(): void
    {
        $email = new EmailDomain(null);

        $this->assertNull($email->classification, 'classification');
        $this->assertNull($email->firstSeen, 'firstSeen');
        $this->assertNull($email->risk, 'risk');
        $this->assertNull($email->visit->hasRedirect, 'visit->hasRedirect');
        $this->assertNull($email->visit->lastVisitedOn, 'visit->lastVisitedOn');
        $this->assertNull($email->visit->status, 'visit->status');
        $this->assertNull($email->volume, 'volume');

        $this->assertSame(
            [],
            $email->jsonSerialize(),
            'jsonSerialize'
        );
    }

    public function testEmailDomainPartial(): void
    {
        $array = [
            'first_seen' => '2017-01-02',
            'risk' => 42.5,
        ];
        $email = new EmailDomain($array);

        $this->assertNull($email->classification, 'classification is null');

        $this->assertSame(
            $array['first_seen'],
            $email->firstSeen,
            'firstSeen'
        );

        $this->assertSame(
            $array['risk'],
            $email->risk,
            'risk'
        );

        $this->assertNull($email->volume, 'volume is null');

        $this->assertSame(
            $array,
            $email->jsonSerialize(),
            'jsonSerialize only includes present fields'
        );
    }

    public function testEmailDomainClassificationValues(): void
    {
        $classifications = ['business', 'education', 'government', 'isp_email'];

        foreach ($classifications as $classification) {
            $email = new EmailDomain(['classification' => $classification]);
            $this->assertSame(
                $classification,
                $email->classification,
                "classification value: {$classification}"
            );
        }
    }
}
