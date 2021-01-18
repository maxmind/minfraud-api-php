<?php

declare(strict_types=1);

namespace MaxMind\Test;

use MaxMind\Exception\InvalidInputException;
use MaxMind\MinFraud;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 */
class MinFraudTest extends \MaxMind\Test\MinFraud\ServiceClientTest
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
                ->with(Data::fullRequest())->$service(),
            'response for full request'
        );
    }

    /**
     * @dataProvider services
     */
    public function testFullInsightsRequestBuiltPiecemeal(string $class, string $service): void
    {
        $incompleteMf = $this->createMinFraudRequestWithFullResponse($service)
            ->withEvent(Data::fullRequest()['event'])
            ->withAccount(Data::fullRequest()['account'])
            ->withEmail(Data::fullRequest()['email'])
            ->withBilling(Data::fullRequest()['billing'])
            ->withShipping(Data::fullRequest()['shipping'])
            ->withPayment(Data::fullRequest()['payment'])
            ->withCreditCard(Data::fullRequest()['credit_card'])
            ->withCustomInputs(Data::fullRequest()['custom_inputs'])
            ->withOrder(Data::fullRequest()['order'])
            ->withShoppingCartItem(Data::fullRequest()['shopping_cart'][0]);

        $mf = $incompleteMf
            ->withShoppingCartItem(Data::fullRequest()['shopping_cart'][1])
            ->withDevice(Data::fullRequest()['device']);

        $responseMeth = $service . 'FullResponse';
        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $mf->$service(),
            'response for full request built piece by piece'
        );

        $this->assertNotEquals(
            $mf,
            $incompleteMf,
            'intermediate object not mutated'
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
        $class = new \ReflectionClass(\MaxMind\MinFraud::class);
        $prop = $class->getProperty('content');
        $prop->setAccessible(true);

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
        $class = new \ReflectionClass(\MaxMind\MinFraud::class);
        $prop = $class->getProperty('content');
        $prop->setAccessible(true);

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
                'device' => ['ip_address' => '1.1.1.1'],
                'billing' => [
                    'first_name' => 'firstname',
                    'last_name' => null,
                ],
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
            ->withDevice(['ip_address' => '1.1.1.1'])
            ->withBilling([
                'first_name' => 'firstname',
                'last_name' => null,
            ])
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
                'device' => ['ip_address' => '1.1.1.1'],
                'billing' => ['first_name' => 'firstname'],
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
        )->withDevice($device)->$service();

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
        )->withDevice($device)->$service();

        $responseMeth = $service . 'FullResponse';

        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $got
        );
    }

    /**
     * @dataProvider withMethods
     */
    public function testUnknownKeys(string $method): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Must have keys');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['unknown' => 'some value']);
    }

    public function withMethods(): array
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
        $this->expectExceptionMessage('must be an MD5');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['address' => $md5]);
    }

    public function badMd5s(): array
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
        $this->expectExceptionMessage('must be an ISO 3166-2');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['region' => $region]);
    }

    public function badRegions(): array
    {
        return [
            ['withBilling', 'AAAAA'],
            ['withBilling', 'aaa'],
            ['withShipping', 'AAAAA'],
            ['withShipping', 'aaa'],
        ];
    }

    /**
     * @dataProvider badCountryCodes
     */
    public function testBadCountryCode(string $method, string $code): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a valid country');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['country' => $code]);
    }

    public function badCountryCodes(): array
    {
        return [
            ['withBilling', 'A'],
            ['withBilling', '1'],
            ['withBilling', 'MAA'],
            ['withShipping', 'A'],
            ['withShipping', 'MAA'],
            ['withShipping', '1'],
        ];
    }

    /**
     * @dataProvider badPhoneCodes
     */
    public function testBadPhoneCodes(string $method, string $key, string $code): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a valid telephone country code');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method([$key => $code]);
    }

    public function badPhoneCodes(): array
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
        $this->expectExceptionMessage('delivery_speed must be in');

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
        $this->expectExceptionMessage('must validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['issuer_id_number' => $iin]);
    }

    public function badIins(): array
    {
        return [
            ['12345'],
            ['1234567'],
            ['a23456'],
        ];
    }

    /**
     * @dataProvider badLast4Digits
     */
    public function testCreditCardWithBadLast4(string $last4): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['last_4_digits' => $last4]);
    }

    public function badLast4Digits(): array
    {
        return [
            ['12345'],
            ['123'],
            ['a234'],
        ];
    }

    /**
     * @dataProvider numericToken
     */
    public function testCreditCardWithNumericToken(string $token): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must not validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    public function numericToken(): array
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
        $this->expectExceptionMessage('must validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    public function invalidRangeToken(): array
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
        $this->expectExceptionMessage('must validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    public function longToken(): array
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

    public function goodToken(): array
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
        $this->expectExceptionMessage('must have a length');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard([$key => 'Aa']);
    }

    public function avsAndCvv(): array
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
        $this->expectExceptionMessage('must be an IP address');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => $ip]);
    }

    public function badIps(): array
    {
        return [
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

    public function negativeSessionAge(): array
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
        $this->expectExceptionMessage('must be a float number');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    public function badSessionAge(): array
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
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    public function goodSessionAge(): array
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
        $this->expectExceptionMessage('must have a length between 1 and 255');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_id' => $id]);
    }

    public function badSessionId(): array
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

    public function goodSessionId(): array
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

    public function goodIps(): array
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
        $this->expectExceptionMessage('must');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['domain' => $domain]);
    }

    public function badDomains(): array
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

    public function goodDomains(): array
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

    public function goodTimes(): array
    {
        $tests = [
            ['2014-04-12T23:20:50+01:00'],
            ['2014-04-12T23:20:50Z'],
        ];

        if (\PHP_VERSION_ID >= 70300) {
            array_push(
                $tests,
                ['2014-04-12T23:20:50.052+01:00'],
                ['2014-04-12T23:20:50.052Z']
            );
        }

        return $tests;
    }

    public function testBadEventTime(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be a valid date');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['time' => '2014/04/04 19:20']);
    }

    public function testBadEventType(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be');

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
        $this->expectExceptionMessage('must validate against');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['currency' => $currency]);
    }

    public function badCurrency(): array
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
        $this->expectExceptionMessageMatches('/must be an? URL/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['referrer_uri' => $uri]);
    }

    public function badReferrerUri(): array
    {
        return [
            ['/blah/'],
            ['www.mm.com'],
        ];
    }

    public function testBadPaymentProcessor(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be');

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

    public function validAmounts(): array
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
        $this->expectExceptionMessageMatches('/(must be greater than or equal to 0|must be a float)/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['amount' => $value]);
    }

    public function invalidAmounts(): array
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
        $this->expectExceptionMessageMatches('/(must be greater than or equal to 0|must be a float)/');

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
        $this->expectExceptionMessageMatches('/(must be greater than 0|must be an int)/');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['quantity' => $value]);
    }

    public function invalidQuantities(): array
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
        $this->expectExceptionMessage('Must have keys');

        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem([['price' => 1]]);
    }

    public function services(): array
    {
        return [
            ['\MaxMind\MinFraud\Model\Factors', 'factors'],
            ['\MaxMind\MinFraud\Model\Insights', 'insights'],
            ['\MaxMind\MinFraud\Model\Score', 'score'],
        ];
    }

    /**
     * @dataProvider badCustomInputs
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

    public function badCustomInputs(): array
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
