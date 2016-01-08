<?php

namespace MaxMind\Test;

use MaxMind\MinFraud;
use MaxMind\Test\MinFraudData as Data;
use MaxMind\WebService\Client;

class MinFraudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider services
     */
    public function testFullRequest($class, $service)
    {
        $responseMeth = $service . 'FullResponse';
        $this->assertEquals(
            new $class(Data::$responseMeth()),
            $this->createMinFraudRequestWithFullResponse($service)
                ->with(Data::$fullRequest)->$service(),
            'response for full request'
        );
    }

    /**
     * @dataProvider services
     */
    public function testFullInsightsRequestBuiltPiecemeal($class, $service)
    {
        $incompleteMf = $this->createMinFraudRequestWithFullResponse($service)
            ->withEvent(Data::$fullRequest['event'])
            ->withAccount(Data::$fullRequest['account'])
            ->withEmail(Data::$fullRequest['email'])
            ->withBilling(Data::$fullRequest['billing'])
            ->withShipping(Data::$fullRequest['shipping'])
            ->withPayment(Data::$fullRequest['payment'])
            ->withCreditCard(Data::$fullRequest['credit_card'])
            ->withOrder(Data::$fullRequest['order'])
            ->withShoppingCartItem(Data::$fullRequest['shopping_cart'][0]);

        $mf = $incompleteMf
            ->withShoppingCartItem(Data::$fullRequest['shopping_cart'][1])
            ->withDevice(Data::$fullRequest['device']);

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
            array(
                'locales' => array('fr')
            )
        )->with(Data::$fullRequest)->insights();

        $this->assertEquals(
            'Royaume-Uni',
            $insights->ipAddress->country->name,
            'locales setting made it to the GeoIP2 models'
        );
    }

    public function testRequestsWithNulls()
    {
        $insights = $this->createNullRequest()
            ->with(array(
                'device' => array('ip_address' => '1.1.1.1'),
                'billing' => array(
                    'first_name' => 'firstname',
                    'last_name' => null,
                ),
                'shopping_cart' => array(
                    array(
                        'category' => 'catname',
                        'item_id' => null,
                    )
                )
            ))->insights();

        $this->assertEquals(
            0.01,
            $insights->riskScore,
            'expected riskScore'
        );
    }

    public function testRequestsWithNullsPiecemeal()
    {
        $insights = $this->createNullRequest()
            ->withDevice(array('ip_address' => '1.1.1.1'))
            ->withBilling(array(
                'first_name' => 'firstname',
                'last_name' => null,
            ))
            ->withShoppingCartItem(array(
                'category' => 'catname',
                'item_id' => null,
            ))
            ->insights();

        $this->assertEquals(
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
            array(),
            array(
                'device' => array('ip_address' => '1.1.1.1'),
                'billing' => array('first_name' => 'firstname'),
                'shopping_cart' => array(array('category' => 'catname'))
            )
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     * @dataProvider services
     */
    public function testMissingIpAddress($class, $service)
    {
        $this->createMinFraudRequestWithFullResponse($service, 0)
            ->with(Data::$fullRequest)
            ->withDevice(array())->$service();
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Key ip_address must be present
     * @dataProvider services
     */
    public function testMissingIpAddressWithoutValidation($class, $service)
    {
        $this->createMinFraudRequestWithFullResponse(
            $service,
            0,
            array('validateInput' => false)
        )->with(Data::$fullRequest)
            ->withDevice(array())->$service();
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     * @dataProvider withMethods
     */
    public function testUnknownKeys($method)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(array('unknown' => 'some value'));
    }

    public function withMethods()
    {
        return array(
            array('withEvent'),
            array('withAccount'),
            array('withEmail'),
            array('withBilling'),
            array('withShipping'),
            array('withPayment'),
            array('withCreditCard'),
            array('withOrder'),
            array('withShoppingCartItem'),
        );
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an MD5
     * @dataProvider badMd5s
     */
    public function testAccountWithBadUsernameMd5($md5)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withAccount(array('username_md5' => $md5));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an MD5
     * @dataProvider badMd5s
     */
    public function testEmailWithBadAddress($md5)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(array('address' => $md5));
    }

    public function badMd5s()
    {
        return array(
            array('14c4b06b824ec593239362517f538b2'),
            array('14c4b06b824ec593239362517f538b29a'),
            array('notvalid'),
            array('invalid @email.org')
        );
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an ISO 3166-2
     * @dataProvider badRegions
     */
    public function testBadRegions($method, $region)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(array('region' => $region));
    }

    public function badRegions()
    {
        return array(
            array('withBilling', 'AAAAA'),
            array('withBilling', 'aaa'),
            array('withShipping', 'AAAAA'),
            array('withShipping', 'aaa'),
        );
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid country
     * @dataProvider badCountryCodes
     */
    public function testBadCountryCode($method, $code)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(array('country' => $code));
    }

    public function badCountryCodes()
    {
        return array(
            array('withBilling', 'A'),
            array('withBilling', '1'),
            array('withBilling', 'MAA'),
            array('withShipping', 'A'),
            array('withShipping', 'MAA'),
            array('withShipping', '1'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid telephone country code
     * @dataProvider badPhoneCodes
     */
    public function testBadPhoneCodes($method, $key, $code)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->$method(array($key => $code));
    }

    public function badPhoneCodes()
    {
        return array(
            array('withBilling', 'phone_country_code', '12344'),
            array('withBilling', 'phone_country_code', '12a'),
            array('withShipping', 'phone_country_code', '12344'),
            array('withShipping', 'phone_country_code', '12a'),
            array('withCreditCard', 'bank_phone_country_code', '12344'),
            array('withCreditCard', 'bank_phone_country_code', '12a'),
        );
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage delivery_speed must be in
     */
    public function testBadDeliverySpeed()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShipping(array('delivery_speed' => 'slow'));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badIins
     */
    public function testBadIin($iin)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(array('issuer_id_number' => $iin));
    }

    public function badIins()
    {
        return array(
            array('12345'),
            array('1234567'),
            array('a23456'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badLast4Digits
     */
    public function testCreditCardWithBadLast4($last4)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(array('last_4_digits' => $last4));
    }

    public function badLast4Digits()
    {
        return array(
            array('12345'),
            array('123'),
            array('a234'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must have a length
     * @dataProvider avsAndCvv
     */
    public function testAvsAndCCv($key)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withCreditCard(array($key => 'Aa'));
    }

    public function avsAndCvv()
    {
        return array(
            array('avs_result'),
            array('cvv_result'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an IP address
     * @dataProvider badIps
     */
    public function testBadIps($ip)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(array('ip_address' => $ip));
    }

    public function badIps()
    {
        return array(
            array('1.2.3.'),
            array('299.1.1.1'),
            array('::AF123'),
        );
    }

    /**
     * @dataProvider goodIps
     */
    public function testGoodIps($ip)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withDevice(array('ip_address' => $ip));
    }

    public function goodIps()
    {
        return array(
            array('1.2.3.4'),
            array('2001:db8:0:0:1:0:0:1'),
            array('::FFFF:1.2.3.4'),
        );
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must
     * @dataProvider badDomains
     */
    public function testBadDomains($domain)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(array('domain' => $domain));
    }

    public function badDomains()
    {
        return array(
            array('bad'),
            array(' bad.com'),
        );
    }

    /**
     * @dataProvider goodDomains
     */
    public function testGoodDomains($domain)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEmail(array('domain' => $domain));
    }

    public function goodDomains()
    {
        return array(
            array('maxmind.com'),
            array('www.bbc.co.uk'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be a valid date
     */
    public function testBadEventTime()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(array('time' => '2014/04/04 19:20'));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be
     */
    public function testBadEventType()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withEvent(array('type' => 'unknown'));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must validate against
     * @dataProvider badCurrency
     */
    public function testBadCurrency($currency)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(array('currency' => $currency));
    }

    public function badCurrency()
    {
        return array(
            array('usd'),
            array('US'),
            array('US1'),
            array('USDD'),
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be an URL
     * @dataProvider badReferrerUri
     */
    public function testBadReferrerUri($uri)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(array('referrer_uri' => $uri));
    }

    public function badReferrerUri()
    {
        return array(
            array('/blah/'),
            array('www.mm.com'),

        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be
     */
    public function testBadPaymentProcessor()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withPayment(array('processor' => 'unknown'));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be a float)
     * @dataProvider nonPositiveValues
     */
    public function testBadOrderAmount($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withOrder(array('amount' => $value));
    }

    public function nonPositiveValues()
    {
        return array(
            array(0),
            array(-1),
            array('afdaf')
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be a float)
     * @dataProvider nonPositiveValues
     */
    public function testBadShoppingCartItemPrice($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(array('price' => $value));
    }


    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessageRegExp (must be greater than 0|must be an int)
     * @dataProvider nonPositiveValues
     */
    public function testBadShoppingCartItemQuantity($value)
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(array('quantity' => $value));
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     */
    public function testBadShoppingCartItemWithDoubleArray()
    {
        $this->createMinFraudRequestWithFullResponse(
            'insights',
            0
        )->withShoppingCartItem(array(array('price' => 1)));
    }

    public function services()
    {
        return array(
            array('\MaxMind\MinFraud\Model\Insights', 'insights'),
            array('\MaxMind\MinFraud\Model\Score', 'score')
        );
    }

    private function createMinFraudRequestWithFullResponse(
        $service,
        $callsToRequest = 1,
        $options = array(),
        $request = null
    ) {
        if ($request === null) {
            $request = Data::$fullRequest;
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
        $options = array(),
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
            ->willReturn(array($statusCode, $contentType, $responseBody));

        $factory = $this->getMockBuilder(
            'MaxMind\\WebService\\Http\\RequestFactory'
        )->getMock();

        $host = isset($options['host']) ? $options['host'] : 'minfraud.maxmind.com';

        $url = 'https://' . $host . '/minfraud/v2.0/' . $service;

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '
            . base64_encode($userId . ':' . $licenseKey),
            'Accept: application/json',
        );

        if (isset($options['caBundle'])) {
            $caBundle = $options['caBundle'];
        } else {
            $reflectionClass = new \ReflectionClass('MaxMind\\WebService\\Client');
            $file = $reflectionClass->getFileName();
            $caBundle = dirname($file) . '/cacert.pem';
        }

        $curlVersion = curl_version();
        $factory->expects($this->exactly($callsToRequest))
            ->method('request')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    array(
                        'headers' => $headers,
                        'userAgent' => 'minFraud-API/' . MinFraud::VERSION
                            . ' MaxMind-WS-API/' . Client::VERSION
                            . ' PHP/' . PHP_VERSION
                            . ' curl/' . $curlVersion['version'],
                        'connectTimeout' => isset($options['connectTimeout'])
                            ? $options['connectTimeout'] : null,
                        'timeout' => isset($options['timeout'])
                            ? $options['timeout'] : null,
                        'caBundle' => $caBundle,
                    )
                )
            )->willReturn($stub);

        $options['httpRequestFactory'] = $factory;

        return new Minfraud(
            $userId,
            $licenseKey,
            $options
        );
    }
}
