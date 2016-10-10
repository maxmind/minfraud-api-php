<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Issuer;

class IssuerTest extends \PHPUnit_Framework_TestCase
{
    public function testIssuer()
    {
        $array = [
            'name' => 'Bank',
            'matches_provided_name' => false,
            'phone_number' => '123-321-3213',
            'matches_provided_phone_number' => true,
        ];
        $issuer = new Issuer($array);

        $this->assertSame(
            $array['name'],
            $issuer->name,
            'issuer name'
        );

        $this->assertSame(
            $array['matches_provided_name'],
            $issuer->matchesProvidedName,
            'issuer name matches'
        );

        $this->assertSame(
            $array['phone_number'],
            $issuer->phoneNumber,
            'issuer phone number'
        );

        $this->assertSame(
            $array['matches_provided_phone_number'],
            $issuer->matchesProvidedPhoneNumber,
            'issuer phone number matches'
        );

        $this->assertSame(
            $array,
            $issuer->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
