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
            $insights->ipLocation->country->name,
            'locales setting made it to the GeoIP2 models'
        );
    }

    /**
     * @expectedException MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Key ip_address must be present
     * @dataProvider services
     */
    public function testMissingIpAddress($class, $service)
    {
        $this->createMinFraudRequestWithFullResponse($service, 0)
             ->with(Data::$fullRequest)
             ->withDevice(array())->$service();
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
        $options = array()
    ) {
        $responseMeth = $service . 'FullResponse';
        return $this->createMinFraudRequest(
            $service,
            Data::$fullRequest,
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
            'Content-type: application/json',
            'Authorization: Basic '
            . base64_encode($userId . ':' . $licenseKey),
            'Accept: application/json',
        );

        if (isset($options['caBundle'])) {
            $caBundle = $options['caBundle'];
        } else {
            $file = (new \ReflectionClass('MaxMind\\WebService\\Client'))->getFileName();
            $caBundle = dirname($file) . '/cacert.pem';
        }

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
                            . ' curl/' . curl_version()['version'],
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
