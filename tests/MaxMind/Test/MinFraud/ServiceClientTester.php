<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud;

use Composer\CaBundle\CaBundle;
use MaxMind\MinFraud\ServiceClient;
use MaxMind\WebService\Client;
use MaxMind\WebService\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
abstract class ServiceClientTester extends TestCase
{
    /**
     * @param array<string, mixed> $requestContent
     * @param                      $responseBody   string|null
     * @param array<string, mixed> $options
     */
    protected function createRequest(
        string $class,
        string $urlTail,
        array $requestContent,
        int $statusCode,
        string $contentType,
        ?string $responseBody,
        array $options = [],
        int $callsToRequest = 1
    ): ServiceClient {
        $userId = 1;
        $licenseKey = 'abcdefghij';

        $stub = $this->createMock(Request::class);

        $stub->expects($this->exactly($callsToRequest))
            ->method('post')
            ->with($this->equalTo(json_encode($requestContent)))
            ->willReturn([$statusCode, $contentType, $responseBody]);

        $factory = $this->getMockBuilder(
            'MaxMind\WebService\Http\RequestFactory'
        )->getMock();

        $host = isset($options['host']) ? $options['host'] : 'minfraud.maxmind.com';

        $url = 'https://' . $host . '/minfraud/v2.0/' . $urlTail;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic '
            . base64_encode($userId . ':' . $licenseKey),
            'Accept: application/json',
        ];

        if (isset($options['caBundle'])) {
            $caBundle = $options['caBundle'];
        } else {
            $curlVersion = curl_version();

            // On macOS, when the SSL version is "SecureTransport", the system's
            // keychain will be used.
            $caBundle = $curlVersion['ssl_version'] === 'SecureTransport'
              ? null : CaBundle::getSystemCaRootBundlePath();
        }

        $curlVersion = curl_version();
        $factory->expects($this->exactly($callsToRequest))
            ->method('request')
            ->with(
                $this->equalTo($url),
                $this->equalTo(
                    [
                        'headers' => $headers,
                        'userAgent' => 'minFraud-API/' . $class::VERSION
                            . ' MaxMind-WS-API/' . Client::VERSION
                            . ' PHP/' . \PHP_VERSION
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

        return new $class(
            $userId,
            $licenseKey,
            $options
        );
    }
}
