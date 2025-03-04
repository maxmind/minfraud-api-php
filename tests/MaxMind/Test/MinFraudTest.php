<?php

declare(strict_types=1);

namespace MaxMind\Test;

use MaxMind\Exception\InvalidInputException;
use MaxMind\MinFraud;
use MaxMind\Test\MinFraud\ServiceClientTester;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 *
 * @internal
 */
class MinFraudTest extends ServiceClientTester
{
    /**
     * @dataProvider services
     */
    public function testFullRequest(string $class, string $service): void
    {
        $responseMeth = $service . 'FullResponse';
        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $this->createMinFraudRequestWithFullResponse($service)
                ->with(Data::fullRequest())->{$service}(),
            'response for full request'
        );
    }

    /**
     * @dataProvider services
     */
    public function testFullInsightsRequestBuiltPiecemeal(string $class, string $service): void
    {
        $incompleteMf = $this->createMinFraudRequestWithFullResponse($service)
            ->withAccount(Data::fullRequest()['account'])
            ->withBilling(Data::fullRequest()['billing'])
            ->withCreditCard(Data::fullRequest()['credit_card'])
            ->withCustomInputs(Data::fullRequest()['custom_inputs'])
            ->withDevice(Data::fullRequest()['device'])
            ->withEmail(Data::fullRequest()['email'])
            ->withEvent(Data::fullRequest()['event'])
            ->withOrder(Data::fullRequest()['order'])
            ->withPayment(Data::fullRequest()['payment'])
            ->withShipping(Data::fullRequest()['shipping'])
            ->withShoppingCartItem(Data::fullRequest()['shopping_cart'][0]);

        $mf = $incompleteMf
            ->withShoppingCartItem(Data::fullRequest()['shopping_cart'][1]);

        $responseMeth = $service . 'FullResponse';
        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $mf->{$service}(),
            'response for full request built piece by piece'
        );

        $this->assertNotEquals(
            $mf,
            $incompleteMf,
            'intermediate object not mutated'
        );
    }

    /**
     * @dataProvider services
     */
    public function testFullInsightsRequestUsingNamedArgs(string $class, string $service): void
    {
        $mf = $this->createMinFraudRequestWithFullResponse($service)
            ->withAccount(
                userId: '3132',
                usernameMd5: '570a90bfbf8c7eab5dc5d4e26832d5b1'
            )
            ->withBilling(
                firstName: 'First',
                lastName: 'Last',
                company: 'Company',
                address: '101 Address Rd.',
                address2: 'Unit 5',
                city: 'City of Thorns',
                region: 'CT',
                country: 'US',
                postal: '06510',
                phoneNumber: '123-456-7890',
                phoneCountryCode: '1'
            )
            ->withCreditCard(
                country: 'US',
                issuerIdNumber: '411111',
                lastDigits: '7643',
                bankName: 'Bank of No Hope',
                bankPhoneCountryCode: '1',
                bankPhoneNumber: '123-456-1234',
                avsResult: 'Y',
                cvvResult: 'N',
                token: '123456abc1234',
                was3dSecureSuccessful: true
            )
            ->withCustomInputs([
                'boolean_input' => true,
                'float_input' => 12.1,
                'integer_input' => 3123,
                'string_input' => 'This is a string input.',
            ])
            ->withDevice(
                acceptLanguage: 'en-US,en;q=0.8',
                ipAddress: '152.216.7.110',
                sessionAge: 3600.5,
                sessionId: 'foobar',
                userAgent: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
            )
            ->withEmail(
                address: '977577b140bfb7c516e4746204fbdb01',
                domain: 'maxmind.com'
            )
            ->withEvent(
                transactionId: 'txn3134133',
                shopId: 's2123',
                time: '2014-04-12T23:20:50+00:00',
                type: 'purchase'
            )
            ->withOrder(
                amount: 323.21,
                currency: 'USD',
                discountCode: 'FIRST',
                affiliateId: 'af12',
                subaffiliateId: 'saf42',
                isGift: true,
                hasGiftMessage: false,
                referrerUri: 'http://www.amazon.com/'
            )
            ->withPayment(
                processor: 'stripe',
                wasAuthorized: false,
                declineCode: 'invalid number'
            )
            ->withShipping(
                firstName: 'ShipFirst',
                lastName: 'ShipLast',
                company: 'ShipCo',
                address: '322 Ship Addr. Ln.',
                address2: 'St. 43',
                city: 'Nowhere',
                region: 'OK',
                country: 'US',
                postal: '73003',
                phoneNumber: '123-456-0000',
                phoneCountryCode: '1',
                deliverySpeed: 'same_day'
            )
            ->withShoppingCartItem(
                category: 'pets',
                itemId: 'ad23232',
                quantity: 2,
                price: 20.43
            )
            ->withShoppingCartItem(
                category: 'beauty',
                itemId: 'bst112',
                quantity: 1,
                price: 100.0
            );

        $responseMeth = $service . 'FullResponse';
        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $mf->{$service}(),
            'response for full request built piece by piece'
        );
    }

    public function testLocalesOption(): void
    {
        $insights = $this->createMinFraudRequestWithFullResponse(
            'insights',
            1,
            [
                'locales' => ['fr'],
            ]
        )->with(Data::fullRequest())->insights();

        $this->assertSame(
            'Royaume-Uni',
            $insights->ipAddress->country->name,
            'locales setting made it to the GeoIP2 models'
        );
    }

    public function testEmailHashingDisabled(): void
    {
        // Reflection isn't ideal, but this is the easiest way to check.
        $class = new \ReflectionClass(MinFraud::class);
        $prop = $class->getProperty('content');

        $client = $this->createMinFraudRequestWithFullResponse(
            'insights',
            0,
            ['hashEmail' => false],
        )->withEmail(['address' => 'test@gmail.com']);

        $this->assertSame(
            ['email' => ['address' => 'test@gmail.com']],
            $prop->getValue($client),
            'hashing disabled using withEmail',
        );

        $client = $client->with(['email' => ['address' => 'test@yahoo.com']]);

        $this->assertSame(
            ['email' => ['address' => 'test@yahoo.com']],
            $prop->getValue($client),
            'hashing disabled using with',
        );

        $client = $this->createMinFraudRequestWithFullResponse(
            'insights',
            0,
        )->withEmail(['address' => 'test@gmail.com']);

        $this->assertSame(
            ['email' => ['address' => 'test@gmail.com']],
            $prop->getValue($client),
            'hashing is disabled by default',
        );
    }

    public function testEmailHashingEnabled(): void
    {
        // Reflection isn't ideal, but this is the easiest way to check.
        $class = new \ReflectionClass(MinFraud::class);
        $prop = $class->getProperty('content');

        $client = $this->createMinFraudRequestWithFullResponse(
            'insights',
            0,
            ['hashEmail' => true],
        )->withEmail(['address' => 'test@gmail.com']);

        $this->assertSame(
            [
                'email' => [
                    'address' => '1aedb8d9dc4751e229a335e371db8058',
                    'domain' => 'gmail.com',
                ],
            ],
            $prop->getValue($client),
            'hashing enabled using withEmail',
        );

        $client = $client->with(['email' => ['address' => 'test@yahoo.com']]);

        $this->assertSame(
            [
                'email' => [
                    'address' => '88e478531ab3bc303f1b5da82c2e9bbb',
                    'domain' => 'yahoo.com',
                ],
            ],
            $prop->getValue($client),
            'hashing enabled using with',
        );
    }

    public function testRequestsWithNulls(): void
    {
        $insights = $this->createNullRequest()
            ->with([
                'billing' => [
                    'first_name' => 'firstname',
                    'last_name' => null,
                ],
                'device' => ['ip_address' => '1.1.1.1'],
                'shopping_cart' => [
                    [
                        'category' => 'catname',
                        'item_id' => null,
                    ],
                ],
            ])->insights();

        $this->assertSame(
            0.01,
            $insights->riskScore,
            'expected riskScore'
        );
    }

    public function testRequestsWithNullsPiecemeal(): void
    {
        $insights = $this->createNullRequest()
            ->withBilling([
                'first_name' => 'firstname',
                'last_name' => null,
            ])
            ->withDevice(['ip_address' => '1.1.1.1'])
            ->withShoppingCartItem([
                'category' => 'catname',
                'item_id' => null,
            ])
            ->insights();

        $this->assertSame(
            0.01,
            $insights->riskScore,
            'expected riskScore'
        );
    }

    private function createNullRequest(): MinFraud
    {
        return $this->createMinFraudRequestWithFullResponse(
            'insights',
            1,
            [],
            [
                'billing' => ['first_name' => 'firstname'],
                'device' => ['ip_address' => '1.1.1.1'],
                'shopping_cart' => [['category' => 'catname']],
            ]
        );
    }

    /**
     * @dataProvider services
     */
    public function testMissingIpAddress(string $class, string $service): void
    {
        $device = [
            'session_age' => 1.2,
        ];
        $request = [
            'device' => $device,
        ];

        $got = $this->createMinFraudRequestWithFullResponse(
            $service,
            1,
            [],
            $request
        )->withDevice($device)->{$service}();

        $responseMeth = $service . 'FullResponse';

        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $got
        );
    }

    /**
     * @dataProvider services
     */
    public function testMissingIpAddressWithoutValidation(string $class, string $service): void
    {
        $device = [
            'session_age' => 1.2,
        ];
        $request = [
            'device' => $device,
        ];

        $got = $this->createMinFraudRequestWithFullResponse(
            $service,
            1,
            ['validateInput' => false],
            $request
        )->withDevice($device)->{$service}();

        $responseMeth = $service . 'FullResponse';

        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $got
        );
    }

    /**
     * @dataProvider services
     */
    public function testUserAgentWithoutSessionId(string $class, string $service): void
    {
        $this->createMinFraudRequestWithFullResponse(
            $service,
            request: ['device' => ['user_agent' => 'test']],
        )->withDevice(['user_agent' => 'test'])->{$service}();
    }

    /**
     * @return array<list<string>>
     */
    public static function services(): array
    {
        return [
            ['\MaxMind\MinFraud\Model\Factors', 'factors'],
            ['\MaxMind\MinFraud\Model\Insights', 'insights'],
            ['\MaxMind\MinFraud\Model\Score', 'score'],
        ];
    }

    /**
     * @dataProvider withMethods
     */
    public function testUnknownKeys(string $method): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Unknown keys');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->{$method}(['unknown' => 'some value']);
    }

    /**
     * @return array<list<string>>
     */
    public static function withMethods(): array
    {
        return [
            ['withEvent'],
            ['withAccount'],
            ['withEmail'],
            ['withBilling'],
            ['withShipping'],
            ['withPayment'],
            ['withCreditCard'],
            ['withOrder'],
            ['withShoppingCartItem'],
        ];
    }

    /**
     * @dataProvider badMd5s
     */
    public function testAccountWithBadUsernameMd5(string $md5): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be an MD5');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withAccount(['username_md5' => $md5]);
    }

    /**
     * @dataProvider badMd5s
     */
    public function testEmailWithBadAddress(string $md5): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('is an invalid email address');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['address' => $md5]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badMd5s(): array
    {
        return [
            ['14c4b06b824ec593239362517f538b2'],
            ['14c4b06b824ec593239362517f538b29a'],
            ['notvalid'],
            ['invalid @email.org'],
        ];
    }

    /**
     * @dataProvider badRegions
     */
    public function testBadRegions(string $method, string $region): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid ISO 3166-2 region code');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->{$method}(['region' => $region]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badRegions(): array
    {
        return [
            ['withBilling', 'AAAAA'],
            ['withBilling', 'aaa'],
            ['withShipping', 'AAAAA'],
            ['withShipping', 'aaa'],
        ];
    }

    /**
     * @dataProvider goodCountryCodes
     */
    public function testGoodCountryCode(string $method, string $code): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->{$method}(['country' => $code]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodCountryCodes(): array
    {
        return self::generateTestData(
            ['withBilling', 'withCreditCard', 'withShipping'],
            ['CA', 'US'],
        );
    }

    /**
     * @dataProvider badCountryCodes
     *
     * @param mixed $code
     */
    public function testBadCountryCode(string $method, $code): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected country|valid ISO 3166-1 country code/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->{$method}(['country' => $code]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badCountryCodes(): array
    {
        return self::generateTestData(
            ['withBilling', 'withCreditCard', 'withShipping'],
            [
                'A',
                '1',
                'MAA',
                'USA',
                'Canada',
                1,
                'ca',
            ]
        );
    }

    /**
     * @param list<string> $methods
     * @param list<mixed>  $values
     *
     * @return array<list<string>>
     */
    private static function generateTestData(array $methods, array $values): array
    {
        $tests = [];
        foreach ($methods as $method) {
            foreach ($values as $value) {
                $tests[] = [$method, $value];
            }
        }

        return $tests;
    }

    /**
     * @dataProvider badPhoneCodes
     */
    public function testBadPhoneCodes(string $method, string $key, string $code): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a string of 1 to 4 digits');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->{$method}([$key => $code]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badPhoneCodes(): array
    {
        return [
            ['withBilling', 'phone_country_code', '12344'],
            ['withBilling', 'phone_country_code', '12a'],
            ['withShipping', 'phone_country_code', '12344'],
            ['withShipping', 'phone_country_code', '12a'],
            ['withCreditCard', 'bank_phone_country_code', '12344'],
            ['withCreditCard', 'bank_phone_country_code', '12a'],
        ];
    }

    public function testBadDeliverySpeed(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid delivery speed');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShipping(['delivery_speed' => 'slow']);
    }

    /**
     * @dataProvider badIins
     */
    public function testBadIin(string $iin): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('string of 6 or 8 digits');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['issuer_id_number' => $iin]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badIins(): array
    {
        return [
            ['12345'],
            ['1234567'],
            ['a23456'],
        ];
    }

    /**
     * @dataProvider badLastDigits
     */
    public function testCreditCardWithBadLastDigits(string $lastDigits): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('string of 2 or 4 digits');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['last_digits' => $lastDigits]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badLastDigits(): array
    {
        return [
            ['12345'],
            ['123'],
            ['a234'],
        ];
    }

    public function testCreditCard8DigitIIN4DigitLastDigits(): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['issuer_id_number' => '88888888', 'last_digits' => '1234']);
    }

    public function testCreditCardDeprecatedLast4Digits(): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['issuer_id_number' => '666666', 'last_4_digits' => '1234']);
    }

    /**
     * @dataProvider numericToken
     */
    public function testCreditCardWithNumericToken(string $token): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('card number');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    /**
     * @return array<list<string>>
     */
    public static function numericToken(): array
    {
        return [
            ['123456'],
            ['123456789123456789'],
        ];
    }

    /**
     * @dataProvider invalidRangeToken
     */
    public function testCreditCardWithInvalidRangeToken(string $token): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('string of 1 to 255 printable ASCII characters');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    /**
     * @return array<list<string>>
     */
    public static function invalidRangeToken(): array
    {
        return [
            ["\x20"],
            ["\x7G"],
        ];
    }

    /**
     * @dataProvider longToken
     */
    public function testCreditCardWithLongToken(string $token): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('string of 1 to 255 printable ASCII characters');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    /**
     * @return array<list<string>>
     */
    public static function longToken(): array
    {
        return [
            [str_repeat('x', 256)],
        ];
    }

    /**
     * @dataProvider goodToken
     */
    public function testCreditCardWithGoodToken(string $token): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodToken(): array
    {
        return [
            ['123456abc1234'],
            ["\x21"],
            [str_repeat('1', 20)],
        ];
    }

    /**
     * @dataProvider avsAndCvv
     */
    public function testAvsAndCCv(string $key): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a string of length 1');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard([$key => 'Aa']);
    }

    /**
     * @return array<list<string>>
     */
    public static function avsAndCvv(): array
    {
        return [
            ['avs_result'],
            ['cvv_result'],
        ];
    }

    /**
     * @dataProvider badIps
     */
    public function testBadIps(string $ip): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('is an invalid IP address');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => $ip]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badIps(): array
    {
        return [
            ['1.2.3'],
            ['1.2.3.'],
            ['299.1.1.1'],
            ['::AF123'],
        ];
    }

    /**
     * @dataProvider negativeSessionAge
     */
    public function testNegativeSessionAge(int $age): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be greater than or equal to 0');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    /**
     * @return array<list<int>>
     */
    public static function negativeSessionAge(): array
    {
        return [
            [-1],
        ];
    }

    /**
     * @dataProvider badSessionAge
     */
    public function testBadSessionAge(string $age): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Expected session_age');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['session_age' => $age]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badSessionAge(): array
    {
        return [
            ['X'],
        ];
    }

    /**
     * @dataProvider goodSessionAge
     *
     * @param mixed $age
     */
    public function testGoodSessionAge($age): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['session_age' => $age]);
    }

    /**
     * @return array<list<mixed>>
     */
    public static function goodSessionAge(): array
    {
        return [
            [0],
            [3600],
            [1000.5],
            ['12345'],
        ];
    }

    /**
     * @dataProvider badSessionId
     */
    public function testBadSessionId(string $id): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a string with length between 1 and 255');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_id' => $id]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badSessionId(): array
    {
        return [
            [str_repeat('X', 256)],
        ];
    }

    /**
     * @dataProvider goodSessionId
     */
    public function testGoodSessionId(int $id): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_id' => $id]);
    }

    /**
     * @return array<list<int>>
     */
    public static function goodSessionId(): array
    {
        return [
            [0],
            [3600],
        ];
    }

    /**
     * @dataProvider goodIps
     */
    public function testGoodIps(string $ip): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => $ip]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodIps(): array
    {
        return [
            ['1.2.3.4'],
            ['2001:db8:0:0:1:0:0:1'],
            ['::FFFF:1.2.3.4'],
        ];
    }

    /**
     * @dataProvider badDomains
     */
    public function testBadDomains(string $domain): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid domain name');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['domain' => $domain]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badDomains(): array
    {
        return [
            ['bad'],
            [' bad.com'],
        ];
    }

    /**
     * @dataProvider goodDomains
     */
    public function testGoodDomains(string $domain): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['domain' => $domain]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodDomains(): array
    {
        return [
            ['maxmind.com'],
            ['www.bbc.co.uk'],
            ['ponyville.eq'],
        ];
    }

    /**
     * @dataProvider goodTimes
     */
    public function testGoodEventTimes(string $time): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['time' => $time]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodTimes(): array
    {
        $tests = [
            ['2014-04-12T23:20:50+01:00'],
            ['2014-04-12T23:20:50Z'],
        ];

        array_push(
            $tests,
            ['2014-04-12T23:20:50.052+01:00'],
            ['2014-04-12T23:20:50.052Z']
        );

        return $tests;
    }

    public function testBadEventTime(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid RFC 3339');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['time' => '2014/04/04 19:20']);
    }

    /**
     * @dataProvider goodEventTypes
     */
    public function testGoodEventType(string $good): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['type' => $good]);
    }

    /**
     * @return array<list<string>>
     */
    public static function goodEventTypes(): array
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

    public function testBadEventType(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid event type');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['type' => 'unknown']);
    }

    /**
     * @dataProvider badCurrency
     */
    public function testBadCurrency(string $currency): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid currency code');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['currency' => $currency]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badCurrency(): array
    {
        return [
            ['usd'],
            ['US'],
            ['US1'],
            ['USDD'],
        ];
    }

    /**
     * @dataProvider badReferrerUri
     */
    public function testBadReferrerUri(string $uri): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/valid URL/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['referrer_uri' => $uri]);
    }

    /**
     * @return array<list<string>>
     */
    public static function badReferrerUri(): array
    {
        return [
            ['/blah/'],
            ['www.mm.com'],
        ];
    }

    public function testBadPaymentProcessor(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('valid payment processor');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withPayment(['processor' => 'unknown']);
    }

    /**
     * @dataProvider validAmounts
     */
    public function testGoodOrderAmount(float $value): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['amount' => $value]);
    }

    /**
     * @dataProvider validAmounts
     */
    public function testGoodShoppingCartItemPrice(float $value): void
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['price' => $value]);
    }

    /**
     * @return array<list<float>>
     */
    public static function validAmounts(): array
    {
        return [
            [0],
            [0.001],
            [1],
            [1e14 - 1],
        ];
    }

    /**
     * @dataProvider invalidAmounts
     *
     * @param mixed $value
     */
    public function testBadOrderAmount($value): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected amount|must be greater than or equal to 0/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['amount' => $value]);
    }

    /**
     * @return array<list<mixed>>
     */
    public static function invalidAmounts(): array
    {
        return [
            [-0.001],
            [-1],
            ['afdaf'],
        ];
    }

    /**
     * @dataProvider invalidAmounts
     *
     * @param mixed $value
     */
    public function testBadShoppingCartItemPrice($value): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected price|must be greater than or equal to 0/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['price' => $value]);
    }

    /**
     * @dataProvider invalidQuantities
     *
     * @param mixed $value
     */
    public function testBadShoppingCartItemQuantity($value): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected quantity|must be greater than or equal to 0/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['quantity' => $value]);
    }

    /**
     * @return array<list<mixed>>
     */
    public static function invalidQuantities(): array
    {
        return [
            [-0.001],
            [-1],
            [0.1],
            [1e14],
            ['afdaf'],
        ];
    }

    public function testBadShoppingCartItemWithDoubleArray(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Unknown keys');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
            // We are testing invalid inputs here.
            // @phpstan-ignore-next-line
        )->withShoppingCartItem([['price' => 1]]);
    }

    /**
     * @dataProvider badCustomInputs
     *
     * @param array<string, mixed> $inputs
     */
    public function testBadCustomInputs(array $inputs): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCustomInputs($inputs);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function badCustomInputs(): array
    {
        return [
            [['InvalidKey' => 1]],
            [['too_long' => str_repeat('x', 256)]],
            [['has_newline' => "test\n"]],
            [['too_big' => 1e13]],
            [['too_small' => -1e13]],
            [['too_big_float' => 1e13 - 0.1]],
        ];
    }

    /**
     * @param array<string, mixed>      $options
     * @param array<string, mixed>|null $request
     */
    private function createMinFraudRequestWithFullResponse(
        string $service,
        int $callsToRequest = 1,
        array $options = [],
        ?array $request = null
    ): MinFraud {
        if ($request === null) {
            $request = Data::fullRequest();
        }
        $responseMeth = $service . 'FullResponse';

        return $this->createMinFraudRequest(
            $service,
            $request,
            200,
            'application/vnd.maxmind.com-minfraud-' . $service
            . '+json; charset=UTF-8; version=2.0',
            json_encode(Data::$responseMeth()),
            $options,
            $callsToRequest
        );
    }

    /**
     * @param array<string, mixed> $requestContent
     * @param                      $responseBody   string|null
     * @param array<string, mixed> $options
     */
    private function createMinFraudRequest(
        string $service,
        array $requestContent,
        int $statusCode,
        string $contentType,
        ?string $responseBody,
        array $options = [],
        int $callsToRequest = 1
    ): MinFraud {
        // @phpstan-ignore-next-line
        return $this->createRequest(
            '\MaxMind\MinFraud',
            $service,
            $requestContent,
            $statusCode,
            $contentType,
            $responseBody,
            $options,
            $callsToRequest
        );
    }
}
