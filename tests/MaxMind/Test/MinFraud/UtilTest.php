<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud;

use MaxMind\MinFraud\Util;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class UtilTest extends TestCase
{
    public function testMaybeHashEmail(): void
    {
        $tests = [
            [
                'name' => 'no email',
                'input' => ['device' => ['ip_address' => '1.1.1.1']],
                'expected' => ['device' => ['ip_address' => '1.1.1.1']],
            ],
            [
                'name' => 'null email',
                'input' => ['email' => ['address' => null]],
                'expected' => ['email' => ['address' => null]],
            ],
            [
                'name' => 'empty email',
                'input' => ['email' => ['address' => '']],
                'expected' => ['email' => ['address' => '']],
            ],
            [
                'name' => 'already hashed email',
                'input' => ['email' => ['address' => '757402e689152e0889ab9cf2c5984c65']],
                'expected' => ['email' => ['address' => '757402e689152e0889ab9cf2c5984c65']],
            ],
            [
                'name' => 'simple email',
                'input' => ['email' => ['address' => 'test@maxmind.com']],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'lots of extra whitespace',
                'input' => ['email' => ['address' => '   test@   maxmind.com   ']],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'domain already set',
                'input' => [
                    'email' => ['address' => 'test@maxmind.com', 'domain' => 'google.com'],
                ],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'google.com',
                    ],
                ],
            ],
            [
                'name' => 'uppercase and alias',
                'input' => ['email' => ['address' => 'Test+ignored@MaxMind.com']],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'multiple + signs',
                'input' => ['email' => ['address' => 'Test+ignored+more@maxmind.com']],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'empty alias',
                'input' => ['email' => ['address' => 'test+@maxmind.com']],
                'expected' => [
                    'email' => [
                        'address' => '977577b140bfb7c516e4746204fbdb01',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'Yahoo aliased email address',
                'input' => ['email' => ['address' => 'basename-keyword@yahoo.com']],
                'expected' => [
                    'email' => [
                        'address' => '667a28047b6caade43c7e75f66aab5f5',
                        'domain' => 'yahoo.com',
                    ],
                ],
            ],
            [
                'name' => 'Yahoo email address with + in local part',
                'input' => ['email' => ['address' => 'Test+foo@yahoo.com']],
                'expected' => [
                    'email' => [
                        'address' => 'a5f830c699fd71ad653aa59fa688c6d9',
                        'domain' => 'yahoo.com',
                    ],
                ],
            ],
            [
                'name' => 'Gmail aliased email address',
                'input' => ['email' => ['address' => 'test+alias@gmail.com']],
                'expected' => [
                    'email' => [
                        'address' => '1aedb8d9dc4751e229a335e371db8058',
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
            [
                'name' => 'Gmail email address with typo in domain',
                'input' => ['email' => ['address' => 'test+alias@gmial.com']],
                'expected' => [
                    'email' => [
                        'address' => '1aedb8d9dc4751e229a335e371db8058',
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
            [
                'name' => 'only + in local part',
                'input' => ['email' => ['address' => '+@MaxMind.com']],
                'expected' => [
                    'email' => [
                        'address' => 'aa57884e48f0dda9fc6f4cb2bffb1dd2',
                        'domain' => 'maxmind.com',
                    ],
                ],
            ],
            [
                'name' => 'no domain',
                'input' => ['email' => ['address' => 'test@']],
                'expected' => [
                    'email' => [
                        'address' => '246a848af2f8394e3adbc738dbe43720',
                    ],
                ],
            ],
            [
                'name' => 'Equivalent domain',
                'input' => ['email' => ['address' => 'foo@googlemail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@gmail.com'),
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
            [
                'name' => 'Periods in gmail localpart',
                'input' => ['email' => ['address' => 'foo.bar@gmail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foobar@gmail.com'),
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
            [
                'name' => 'Fastmail alias domain',
                'input' => ['email' => ['address' => 'alias@user.fastmail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('user@fastmail.com'),
                        'domain' => 'user.fastmail.com',
                    ],
                ],
            ],
            [
                'name' => 'Domain with multiple host parts',
                'input' => ['email' => ['address' => 'foo@bar.example.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@bar.example.com'),
                        'domain' => 'bar.example.com',
                    ],
                ],
            ],
            [
                'name' => 'Yahoo domain',
                'input' => ['email' => ['address' => 'foo-bar@ymail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@ymail.com'),
                        'domain' => 'ymail.com',
                    ],
                ],
            ],
            [
                'name' => '.com.com',
                'input' => ['email' => ['address' => 'foo@example.com.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@example.com'),
                        'domain' => 'example.com',
                    ],
                ],
            ],
            [
                'name' => 'Extra characters after .com',
                'input' => ['email' => ['address' => 'foo@example.comfoo']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@example.com'),
                        'domain' => 'example.com',
                    ],
                ],
            ],
            [
                'name' => '.com typo',
                'input' => ['email' => ['address' => 'foo@example.cam']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@example.com'),
                        'domain' => 'example.com',
                    ],
                ],
            ],
            [
                'name' => 'gmail leading digit domain',
                'input' => ['email' => ['address' => 'foo@10000gmail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@gmail.com'),
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
            [
                'name' => 'gmail typo',
                'input' => ['email' => ['address' => 'foo@yahoogmail.com']],
                'expected' => [
                    'email' => [
                        'address' => md5('foo@gmail.com'),
                        'domain' => 'gmail.com',
                    ],
                ],
            ],
        ];

        if (\function_exists('idn_to_ascii')
            && idn_to_ascii('bücher.com', \IDNA_NONTRANSITIONAL_TO_ASCII, \INTL_IDNA_VARIANT_UTS46) === 'xn--bcher-kva.com'
            // This test fails on this combo and it is hard to tell what is going on
            // without actual access to such a machine.
            && (\PHP_OS !== 'Darwin' || \PHP_MAJOR_VERSION !== 7)
        ) {
            array_push(
                $tests,
                [
                    'name' => 'IDN in domain',
                    'input' => ['email' => ['address' => 'test@bücher.com']],
                    'expected' => [
                        'email' => [
                            'address' => '24948acabac551360cd510d5e5e2b464',
                            'domain' => 'xn--bcher-kva.com',
                        ],
                    ],
                ],
            );
        }

        foreach ($tests as &$test) {
            $this->assertSame(
                $test['expected'],
                Util::maybeHashEmail($test['input']),
                $test['name'],
            );
        }
    }
}
