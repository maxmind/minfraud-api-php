<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\ReportTransaction;

use MaxMind\Exception\InvalidInputException;
use MaxMind\MinFraud\ReportTransaction;
use MaxMind\Test\MinFraud\ReportTransaction\ReportTransactionData as Data;

/**
 * @coversNothing
 *
 * @internal
 */
class ReportTransactionTest extends \MaxMind\Test\MinFraud\ServiceClientTest
{
    public function testMinimalRequest(): void
    {
        $this->assertEmpty(
            // @phpstan-ignore-next-line
            $this->createReportTransactionRequest(
                Data::minimalRequest(),
                1
            )->report(Data::minimalRequest()),
            'response for minimal request'
        );
    }

    public function testFullRequest(): void
    {
        $req = Data::fullRequest();
        $this->assertEmpty(
            // @phpstan-ignore-next-line
            $this->createReportTransactionRequest(
                $req
            )->report($req),
            'response for full request'
        );
    }

    public function testRequestsWithNulls(): void
    {
        $req = array_merge(
            Data::minimalRequest(),
            [
                'chargeback_code' => null,
                'maxmind_id' => null,
                'minfraud_id' => null,
                'notes' => null,
                'transaction_id' => null,
            ]
        );
        $this->assertEmpty(
            // @phpstan-ignore-next-line
            $this->createReportTransactionRequest(
                Data::minimalRequest(),
                1
            )->report($req),
            'response from request including nulls'
        );
    }

    /**
     * @dataProvider requestsMissingRequiredFields
     */
    public function testMissingRequiredFields(array $req): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Must have keys');

        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @dataProvider requestsMissingRequiredFields
     */
    public function testMissingRequiredFieldsWithoutValidation(array $req): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be present in request');

        $this->createReportTransactionRequest(
            $req,
            0,
            ['validateInput' => false]
        )->report($req);
    }

    public function requestsMissingRequiredFields(): array
    {
        return [
            'Missing ip_address' => [
                ['tag' => 'not_fraud'],
            ],
            'Missing tag' => [
                ['ip_address' => '1.2.3.4'],
            ],
            'Null ip_address' => [
                array_merge(Data::minimalRequest(), ['ip_address' => null]),
            ],
            'Null tag' => [
                array_merge(Data::minimalRequest(), ['tag' => null]),
            ],
        ];
    }

    public function testUnknownKey(): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('Must have keys');

        $req = array_merge(
            Data::minimalRequest(),
            ['unknown' => 'some_value']
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @dataProvider notStringTypes
     *
     * @param mixed $chargebackCode
     */
    public function testInvalidChargebackCodes($chargebackCode): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('chargeback_code must be of type string');

        $req = array_merge(
            Data::minimalRequest(),
            ['chargeback_code' => $chargebackCode]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @dataProvider notStringTypes
     *
     * @param mixed $notes
     */
    public function testInvalidNotes($notes): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('notes must be of type string');

        $req = array_merge(Data::minimalRequest(), ['notes' => $notes]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @dataProvider notStringTypes
     *
     * @param mixed $transactionId
     */
    public function testInvalidTransactionIds($transactionId): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('transaction_id must be of type string');

        $req = array_merge(
            Data::minimalRequest(),
            ['transaction_id' => $transactionId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function notStringTypes(): array
    {
        return [
            [1],
            [['string']],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider invalidIpAddresses
     */
    public function testInvalidIpAddresses(string $ip): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('ip_address must be an IP address');

        $req = array_merge(
            Data::minimalRequest(),
            ['ip_address' => $ip]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function invalidIpAddresses(): array
    {
        return [
            ['1.2.3.'],
            ['299.1.1.1'],
            ['::AF123'],
        ];
    }

    /**
     * @dataProvider invalidMaxmindIds
     */
    public function testInvalidMaxmindIds(string $maxmindId): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('maxmind_id must have a length of 8');

        $req = array_merge(
            Data::minimalRequest(),
            ['maxmind_id' => $maxmindId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function invalidMaxmindIds(): array
    {
        return [
            ['1234567'],
            ['123456789'],
            [''],
        ];
    }

    /**
     * @dataProvider invalidMinfraudIds
     */
    public function testInvalidMinfraudIds(string $minfraudId): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('minfraud_id must validate against');

        $req = array_merge(
            Data::minimalRequest(),
            ['minfraud_id' => $minfraudId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function invalidMinfraudIds(): array
    {
        return [
            ['1234567812341234123412345678901'],
            ['1234-5678-1234-1234-1234-1234-5678-9012'],
            ['12345678-123412341234-12345678901'],
            ['12345678-1234-1234-1234-1234567890123'],
            ['12345678-1234-1234-1234-12345678901g'],
            [''],
        ];
    }

    /**
     * @dataProvider invalidTags
     */
    public function testInvalidTags(string $tag): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('tag must be in');

        $req = array_merge(Data::minimalRequest(), ['tag' => $tag]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function invalidTags(): array
    {
        return [
            ['risky_business'],
            [''],
        ];
    }

    private function createReportTransactionRequest(
        array $requestContent,
        int $callsToRequest = 1,
        array $options = [],
        int $statusCode = 204,
        string $contentType = 'application/json',
        ?string $responseBody = null
    ): ReportTransaction {
        // @phpstan-ignore-next-line
        return $this->createRequest(
            '\MaxMind\MinFraud\ReportTransaction',
            'transactions/report',
            $requestContent,
            $statusCode,
            $contentType,
            $responseBody,
            $options,
            $callsToRequest
        );
    }
}
