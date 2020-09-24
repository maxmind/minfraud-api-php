<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\ReportTransaction;

use MaxMind\Exception\InvalidInputException;
use MaxMind\Test\MinFraud\ReportTransaction\ReportTransactionData as Data;

/**
 * @coversNothing
 */
class ReportTransactionTest extends \MaxMind\Test\MinFraud\ServiceClientTest
{
    public function testMinimalRequest()
    {
        $this->assertEmpty(
            $this->createReportTransactionRequest(
                Data::minimalRequest(),
                1
            )->report(Data::minimalRequest()),
            'response for minimal request'
        );
    }

    public function testFullRequest()
    {
        $req = Data::fullRequest();
        $this->assertEmpty(
            $this->createReportTransactionRequest(
                $req
            )->report($req),
            'response for full request'
        );
    }

    public function testRequestsWithNulls()
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
            $this->createReportTransactionRequest(
                Data::minimalRequest(),
                1
            )->report($req),
            'response from request including nulls'
        );
    }

    /**
     * @dataProvider requestsMissingRequiredFields
     *
     * @param array $req
     */
    public function testMissingRequiredFields($req)
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
     *
     * @param array $req
     */
    public function testMissingRequiredFieldsWithoutValidation($req)
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('must be present in request');

        $this->createReportTransactionRequest(
            $req,
            0,
            ['validateInput' => false]
        )->report($req);
    }

    public function requestsMissingRequiredFields()
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

    public function testUnknownKey()
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
    public function testInvalidChargebackCodes($chargebackCode)
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
    public function testInvalidNotes($notes)
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
    public function testInvalidTransactionIds($transactionId)
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

    public function notStringTypes()
    {
        return [
            [1],
            [['string']],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider notStringTypes
     *
     * @param mixed $ip
     */
    public function testInvalidIpAddresses($ip)
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

    public function invalidIpAddresses()
    {
        return [
            ['1.2.3.'],
            ['299.1.1.1'],
            ['::AF123'],
        ];
    }

    /**
     * @dataProvider invalidMaxmindIds
     *
     * @param mixed $maxmindId
     */
    public function testInvalidMaxmindIds($maxmindId)
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

    public function invalidMaxmindIds()
    {
        return [
            ['1234567'],
            ['123456789'],
            [''],
        ];
    }

    /**
     * @dataProvider invalidMinfraudIds
     *
     * @param mixed $minfraudId
     */
    public function testInvalidMinfraudIds($minfraudId)
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

    public function invalidMinfraudIds()
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
     *
     * @param mixed $tag
     */
    public function testInvalidTags($tag)
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('tag must be in');

        $req = array_merge(Data::minimalRequest(), ['tag' => $tag]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->report($req);
    }

    public function invalidTags()
    {
        return [
            ['risky_business'],
            [''],
        ];
    }

    private function createReportTransactionRequest(
        $requestContent,
        $callsToRequest = 1,
        $options = [],
        $statusCode = 204,
        $contentType = 'application/json',
        $responseBody = null
    ) {
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
