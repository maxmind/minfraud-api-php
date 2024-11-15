<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\ReportTransaction;

use MaxMind\Exception\InvalidInputException;
use MaxMind\MinFraud\ReportTransaction;
use MaxMind\Test\MinFraud\ReportTransaction\ReportTransactionData as Data;
use MaxMind\Test\MinFraud\ServiceClientTester;

/**
 * @coversNothing
 *
 * @internal
 */
class ReportTransactionTest extends ServiceClientTester
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

    public function testRequiredFields(): void
    {
        $req = [
            'ip_address' => '1.1.1.1',
            'tag' => 'not_fraud',
        ];
        $this->createReportTransactionRequest($req, 1)->report($req);

        $req = [
            'maxmind_id' => '12345678',
            'tag' => 'not_fraud',
        ];
        $this->createReportTransactionRequest($req, 1)->report($req);

        $req = [
            'minfraud_id' => '58fa38d8-4b87-458b-a22b-f00eda1aa20d',
            'tag' => 'not_fraud',
        ];
        $this->createReportTransactionRequest($req, 1)->report($req);

        $req = [
            'tag' => 'not_fraud',
            'transaction_id' => 'abc123',
        ];
        $this->createReportTransactionRequest($req, 1)->report($req);
    }

    /**
     * @dataProvider requestsMissingRequiredFields
     *
     * @param array<string, mixed> $req
     */
    public function testMissingRequiredFields(array $req): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected|is required|must pass at least one of the following/');

        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @dataProvider requestsMissingRequiredFields
     *
     * @param array<string, mixed> $req
     */
    public function testMissingRequiredFieldsWithoutValidation(array $req): void
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessageMatches('/Expected|is required|must pass at least one of the following/');

        $this->createReportTransactionRequest(
            $req,
            0,
            ['validateInput' => false]
        )->report($req);
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    public static function requestsMissingRequiredFields(): array
    {
        return [
            'Missing one of ip_address, maxmind_id, minfraud_id, or transaction_id' => [
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
        $this->expectExceptionMessage('Unknown keys');

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
        $this->expectExceptionMessage('Expected chargeback_code');

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
        $this->expectExceptionMessage('Expected notes');

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
        $this->expectExceptionMessage('Expected transaction_id');

        $req = array_merge(
            Data::minimalRequest(),
            ['transaction_id' => $transactionId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @return list<list<mixed>>
     */
    public static function notStringTypes(): array
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
        $this->expectExceptionMessage('is an invalid IP address');

        $req = array_merge(
            Data::minimalRequest(),
            ['ip_address' => $ip]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @return list<list<string>>
     */
    public static function invalidIpAddresses(): array
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
        $this->expectExceptionMessage('must be 8 characters long');

        $req = array_merge(
            Data::minimalRequest(),
            ['maxmind_id' => $maxmindId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @return list<list<string>>
     */
    public static function invalidMaxmindIds(): array
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
        $this->expectExceptionMessage('must be a valid minFraud ID');

        $req = array_merge(
            Data::minimalRequest(),
            ['minfraud_id' => $minfraudId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @return list<list<string>>
     */
    public static function invalidMinfraudIds(): array
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
        $this->expectExceptionMessage('must be one of');

        $req = array_merge(Data::minimalRequest(), ['tag' => $tag]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    /**
     * @return list<list<string>>
     */
    public static function invalidTags(): array
    {
        return [
            ['risky_business'],
            [''],
        ];
    }

    /**
     * @param array<string, mixed> $requestContent
     * @param array<string, mixed> $options
     * @param                      $responseBody   string|null
     */
    private function createReportTransactionRequest(
        array $requestContent,
        int $callsToRequest = 1,
        array $options = [],
        int $statusCode = 204,
        string $contentType = 'application/json',
        ?string $responseBody = null
    ): ReportTransaction {
        $rv = $this->createRequest(
            '\MaxMind\MinFraud\ReportTransaction',
            'transactions/report',
            $requestContent,
            $statusCode,
            $contentType,
            $responseBody,
            $options,
            $callsToRequest
        );

        if (!$rv instanceof ReportTransaction) {
            throw new \Exception('Unexpected client type!');
        }

        return $rv;
    }
}
