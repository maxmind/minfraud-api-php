<?php

namespace MaxMind\Test;

use Composer\CaBundle\CaBundle;
use MaxMind\MinFraud;
use MaxMind\Test\MinFraudData as Data;
use MaxMind\WebService\Client;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class MinFraudTest extends TestCase
{
    /**
     * @dataProvider services
     *
     * @param mixed $class
     * @param mixed $service
     */
    public function testFullRequest($class, $service)
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
     *
     * @param mixed $class
     * @param mixed $service
     */
    public function testFullInsightsRequestBuiltPiecemeal($class, $service)
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

    public function testLocalesOption()
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

    public function testRequestsWithNulls()
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

    public function testRequestsWithNullsPiecemeal()
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

    private function createNullRequest()
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     * @dataProvider services
     *
     * @param mixed $class
     * @param mixed $service
     */
    public function testMissingIpAddress($class, $service)
    {
        $this->createMinFraudRequestWithFullResponse($service, 0)
            ->with(Data::fullRequest())
            ->withDevice([])->$service();
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Key ip_address must be present
     * @dataProvider services
     *
     * @param mixed $class
     * @param mixed $service
     */
    public function testMissingIpAddressWithoutValidation($class, $service)
    {
        $this->createMinFraudRequestWithFullResponse(
            $service,
            0,
            ['validateInput' => false]
        )->with(Data::fullRequest())
            ->withDevice([])->$service();
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     * @dataProvider withMethods
     *
     * @param mixed $method
     */
    public function testUnknownKeys($method)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['unknown' => 'some value']);
    }

    public function withMethods()
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an MD5
     * @dataProvider badMd5s
     *
     * @param mixed $md5
     */
    public function testAccountWithBadUsernameMd5($md5)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withAccount(['username_md5' => $md5]);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an MD5
     * @dataProvider badMd5s
     *
     * @param mixed $md5
     */
    public function testEmailWithBadAddress($md5)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['address' => $md5]);
    }

    public function badMd5s()
    {
        return [
            ['14c4b06b824ec593239362517f538b2'],
            ['14c4b06b824ec593239362517f538b29a'],
            ['notvalid'],
            ['invalid @email.org'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an ISO 3166-2
     * @dataProvider badRegions
     *
     * @param mixed $method
     * @param mixed $region
     */
    public function testBadRegions($method, $region)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['region' => $region]);
    }

    public function badRegions()
    {
        return [
            ['withBilling', 'AAAAA'],
            ['withBilling', 'aaa'],
            ['withShipping', 'AAAAA'],
            ['withShipping', 'aaa'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid country
     * @dataProvider badCountryCodes
     *
     * @param mixed $method
     * @param mixed $code
     */
    public function testBadCountryCode($method, $code)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(['country' => $code]);
    }

    public function badCountryCodes()
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid telephone country code
     * @dataProvider badPhoneCodes
     *
     * @param mixed $method
     * @param mixed $key
     * @param mixed $code
     */
    public function testBadPhoneCodes($method, $key, $code)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method([$key => $code]);
    }

    public function badPhoneCodes()
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

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage delivery_speed must be in
     */
    public function testBadDeliverySpeed()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShipping(['delivery_speed' => 'slow']);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badIins
     *
     * @param mixed $iin
     */
    public function testBadIin($iin)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['issuer_id_number' => $iin]);
    }

    public function badIins()
    {
        return [
            ['12345'],
            ['1234567'],
            ['a23456'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badLast4Digits
     *
     * @param mixed $last4
     */
    public function testCreditCardWithBadLast4($last4)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['last_4_digits' => $last4]);
    }

    public function badLast4Digits()
    {
        return [
            ['12345'],
            ['123'],
            ['a234'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must not validate against
     * @dataProvider numericToken
     *
     * @param mixed $token
     */
    public function testCreditCardWithNumericToken($token)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(['token' => $token]);
    }

    public function numericToken()
    {
        return [
            ['123456'],
            ['123456789123456789'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider invalidRangeToken
     *
     * @param mixed $token
     */
    public function testCreditCardWithInvalidRangeToken($token)
    {
        $this->createMinFraudRequestWithFullResponse(
                'insights',
                0
                )->withCreditCard(['token' => $token]);
    }

    public function invalidRangeToken()
    {
        return [
                ["\x20"],
                ["\x7G"],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider longToken
     *
     * @param mixed $token
     */
    public function testCreditCardWithLongToken($token)
    {
        $this->createMinFraudRequestWithFullResponse(
                'insights',
                0
                )->withCreditCard(['token' => $token]);
    }

    public function longToken()
    {
        return [
                [str_repeat('x', 256)],
        ];
    }

    /**
     * @dataProvider goodToken
     *
     * @param mixed $token
     */
    public function testCreditCardWithGoodToken($token)
    {
        $this->createMinFraudRequestWithFullResponse(
                'insights',
                0
                )->withCreditCard(['token' => $token]);
    }

    public function goodToken()
    {
        return [
                ['123456abc1234'],
                ["\x21"],
                [str_repeat('1', 20)],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must have a length
     * @dataProvider avsAndCvv
     *
     * @param mixed $key
     */
    public function testAvsAndCCv($key)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard([$key => 'Aa']);
    }

    public function avsAndCvv()
    {
        return [
            ['avs_result'],
            ['cvv_result'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an IP address
     * @dataProvider badIps
     *
     * @param mixed $ip
     */
    public function testBadIps($ip)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => $ip]);
    }

    public function badIps()
    {
        return [
            ['1.2.3.'],
            ['299.1.1.1'],
            ['::AF123'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be greater than or equal to 0
     * @dataProvider negativeSessionAge
     *
     * @param mixed $age
     */
    public function testNegativeSessionAge($age)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    public function negativeSessionAge()
    {
        return [
            [-1],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a float number
     * @dataProvider badSessionAge
     *
     * @param mixed $age
     */
    public function testBadSessionAge($age)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    public function badSessionAge()
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
    public function testGoodSessionAge($age)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_age' => $age]);
    }

    public function goodSessionAge()
    {
        return [
            [0],
            [3600],
            [1000.5],
            ['12345'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must have a length between 1 and 255
     * @dataProvider badSessionId
     *
     * @param mixed $id
     */
    public function testBadSessionId($id)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_id' => $id]);
    }

    public function badSessionId()
    {
        return [
            [str_repeat('X', 256)],
        ];
    }

    /**
     * @dataProvider goodSessionId
     *
     * @param mixed $id
     */
    public function testGoodSessionId($id)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => '1.2.3.4', 'session_id' => $id]);
    }

    public function goodSessionId()
    {
        return [
            [0],
            [3600],
        ];
    }

    /**
     * @dataProvider goodIps
     *
     * @param mixed $ip
     */
    public function testGoodIps($ip)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(['ip_address' => $ip]);
    }

    public function goodIps()
    {
        return [
            ['1.2.3.4'],
            ['2001:db8:0:0:1:0:0:1'],
            ['::FFFF:1.2.3.4'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must
     * @dataProvider badDomains
     *
     * @param mixed $domain
     */
    public function testBadDomains($domain)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['domain' => $domain]);
    }

    public function badDomains()
    {
        return [
            ['bad'],
            [' bad.com'],
        ];
    }

    /**
     * @dataProvider goodDomains
     *
     * @param mixed $domain
     */
    public function testGoodDomains($domain)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(['domain' => $domain]);
    }

    public function goodDomains()
    {
        return [
            ['maxmind.com'],
            ['www.bbc.co.uk'],
            ['ponyville.eq'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid date
     */
    public function testBadEventTime()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['time' => '2014/04/04 19:20']);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be
     */
    public function testBadEventType()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(['type' => 'unknown']);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badCurrency
     *
     * @param mixed $currency
     */
    public function testBadCurrency($currency)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['currency' => $currency]);
    }

    public function badCurrency()
    {
        return [
            ['usd'],
            ['US'],
            ['US1'],
            ['USDD'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp /must be an? URL/
     * @dataProvider badReferrerUri
     *
     * @param mixed $uri
     */
    public function testBadReferrerUri($uri)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['referrer_uri' => $uri]);
    }

    public function badReferrerUri()
    {
        return [
            ['/blah/'],
            ['www.mm.com'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be
     */
    public function testBadPaymentProcessor()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withPayment(['processor' => 'unknown']);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be a float)
     * @dataProvider nonPositiveValues
     *
     * @param mixed $value
     */
    public function testBadOrderAmount($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(['amount' => $value]);
    }

    public function nonPositiveValues()
    {
        return [
            [0],
            [-1],
            ['afdaf'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be a float)
     * @dataProvider nonPositiveValues
     *
     * @param mixed $value
     */
    public function testBadShoppingCartItemPrice($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['price' => $value]);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be an int)
     * @dataProvider nonPositiveValues
     *
     * @param mixed $value
     */
    public function testBadShoppingCartItemQuantity($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(['quantity' => $value]);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     */
    public function testBadShoppingCartItemWithDoubleArray()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem([['price' => 1]]);
    }

    public function services()
    {
        return [
            ['\MaxMind\MinFraud\Model\Factors', 'factors'],
            ['\MaxMind\MinFraud\Model\Insights', 'insights'],
            ['\MaxMind\MinFraud\Model\Score', 'score'],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must
     * @dataProvider badCustomInputs
     *
     * @param mixed $inputs
     */
    public function testBadCustomInputs($inputs)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCustomInputs($inputs);
    }

    public function badCustomInputs()
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
        $service,
        $callsToRequest = 1,
        $options = [],
        $request = null
    ) {
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
        $service,
        $requestContent,
        $statusCode,
        $contentType,
        $responseBody,
        $options = [],
        $callsToRequest = 1
    ) {
        $userId = 1;
        $licenseKey = 'abcdefghij';

        $stub = $this->getMockForAbstractClass(
            'MaxMind\\WebService\\Http\\Request'
        );

        $stub->expects($this->exactly($callsToRequest))
            ->method('post')
            ->with($this->equalTo(json_encode($requestContent)))
            ->willReturn([$statusCode, $contentType, $responseBody]);

        $factory = $this->getMockBuilder(
            'MaxMind\\WebService\\Http\\RequestFactory'
        )->getMock();

        $host = isset($options['host']) ? $options['host'] : 'minfraud.maxmind.com';

        $url = 'https://' . $host . '/minfraud/v2.0/' . $service;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic '
            . base64_encode($userId . ':' . $licenseKey),
            'Accept: application/json',
        ];

        if (isset($options['caBundle'])) {
            $caBundle = $options['caBundle'];
        } else {
            $caBundle = CaBundle::getSystemCaRootBundlePath();
        }

        $curlVersion = curl_version();
        $factory->expects($this->exactly($callsToRequest))
            ->method('request')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    [
                        'headers' => $headers,
                        'userAgent' => 'minFraud-API/' . MinFraud::VERSION
                            . ' MaxMind-WS-API/' . Client::VERSION
                            . ' PHP/' . PHP_VERSION
                            . ' curl/' . $curlVersion['version'],
                        'connectTimeout' => isset($options['connectTimeout'])
                            ? $options['connectTimeout'] : null,
                        'timeout' => isset($options['timeout'])
                            ? $options['timeout'] : null,
                        'proxy' => isset($options['proxy'])
                            ? $options['proxy'] : null,
                        'caBundle' => $caBundle,
                    ]
                )
            )->willReturn($stub);

        $options['httpRequestFactory'] = $factory;

        return new MinFraud(
            $userId,
            $licenseKey,
            $options
        );
    }
}
