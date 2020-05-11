<?php

namespace MaxMind\Test\MinFraud\ReportTransaction;

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
            )->reportTransaction(Data::minimalRequest()),
            'response for minimal request'
        );
    }

    public function testFullRequest()
    {
        $req = Data::fullRequest();
        $this->assertEmpty(
            $this->createReportTransactionRequest(
                $req
            )->reportTransaction($req),
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
            )->reportTransaction($req),
            'response from request including nulls'
        );
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     *
     * @dataProvider requestsMissingRequiredFields
     *
     * @param array $req
     */
    public function testMissingRequiredFields($req)
    {
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage must be present in request
     *
     * @dataProvider requestsMissingRequiredFields
     *
     * @param array $req
     */
    public function testMissingRequiredFieldsWithoutValidation($req)
    {
        $this->createReportTransactionRequest(
            $req,
            0,
            ['validateInput' => false]
        )->reportTransaction($req);
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

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage Must have keys
     */
    public function testUnknownKey()
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['unknown' => 'some_value']
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage chargeback_code must be a string
     *
     * @dataProvider notStringTypes
     *
     * @param mixed $chargebackCode
     */
    public function testInvalidChargebackCodes($chargebackCode)
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['chargeback_code' => $chargebackCode]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage notes must be a string
     *
     * @dataProvider notStringTypes
     *
     * @param mixed $notes
     */
    public function testInvalidNotes($notes)
    {
        $req = array_merge(Data::minimalRequest(), ['notes' => $notes]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage transaction_id must be a string
     *
     * @dataProvider notStringTypes
     *
     * @param mixed $transactionId
     */
    public function testInvalidTransactionIds($transactionId)
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['transaction_id' => $transactionId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
    }

    public function notStringTypes()
    {
        return [
            [1],
        ];
    }

    /**
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage ip_address must be an IP address
     *
     * @dataProvider notStringTypes
     *
     * @param mixed $ip
     */
    public function testInvalidIpAddresses($ip)
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['ip_address' => $ip]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage maxmind_id must have a length between 8 and 8
     *
     * @dataProvider invalidMaxmindIds
     *
     * @param mixed $maxmindId
     */
    public function testInvalidMaxmindIds($maxmindId)
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['maxmind_id' => $maxmindId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage minfraud_id must validate against
     *
     * @dataProvider invalidMinfraudIds
     *
     * @param mixed $minfraudId
     */
    public function testInvalidMinfraudIds($minfraudId)
    {
        $req = array_merge(
            Data::minimalRequest(),
            ['minfraud_id' => $minfraudId]
        );
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
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
     * @expectedException \MaxMind\Exception\InvalidInputException
     * @expectedExceptionMessage tag must be in
     *
     * @dataProvider invalidTags
     *
     * @param mixed $tag
     */
    public function testInvalidTags($tag)
    {
        $req = array_merge(Data::minimalRequest(), ['tag' => $tag]);
        $this->createReportTransactionRequest(
            $req,
            0
        )->reportTransaction($req);
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
