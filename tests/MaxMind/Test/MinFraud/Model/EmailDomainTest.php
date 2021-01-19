<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\EmailDomain;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class EmailDomainTest extends TestCase
{
    public function testEmailDomain(): void
    {
        $array = [
            'first_seen' => '2017-01-02',
        ];
        $email = new EmailDomain($array);

        $this->assertSame(
            $array['first_seen'],
            $email->firstSeen,
            'firstSeen'
        );
    }
}
