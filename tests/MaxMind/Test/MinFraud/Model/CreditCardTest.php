<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\CreditCard;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class CreditCardTest extends TestCase
{
    public function testCreditCard(): void
    {
        $array = [
            'issuer' => [
                'name' => 'Bank',
                'matches_provided_name' => false,
                'phone_number' => '123-321-3213',
                'matches_provided_phone_number' => true,
            ],
            'brand' => 'Visa',
            'country' => 'US',
            'is_business' => true,
            'is_issued_in_billing_address_country' => false,
            'is_prepaid' => true,
            'is_virtual' => true,
            'type' => 'credit',
        ];
        $cc = new CreditCard($array);

        $this->assertSame(
            $array['issuer']['name'],
            $cc->issuer->name,
            'issuer name'
        );

        $this->assertSame(
            $array['issuer']['matches_provided_name'],
            $cc->issuer->matchesProvidedName,
            'issuer name matches'
        );

        $this->assertSame(
            $array['issuer']['phone_number'],
            $cc->issuer->phoneNumber,
            'issuer phone number'
        );

        $this->assertSame(
            $array['issuer']['matches_provided_phone_number'],
            $cc->issuer->matchesProvidedPhoneNumber,
            'issuer phone number matches'
        );

        $this->assertSame(
            $array['brand'],
            $cc->brand,
            'brand'
        );

        $this->assertSame(
            $array['country'],
            $cc->country,
            'country'
        );

        $this->assertSame(
            $array['is_business'],
            $cc->isBusiness,
            'isBusiness'
        );

        $this->assertSame(
            $array['is_issued_in_billing_address_country'],
            $cc->isIssuedInBillingAddressCountry,
            'isIssuedInBillingAddressCountry'
        );

        $this->assertSame(
            $array['is_prepaid'],
            $cc->isPrepaid,
            'isPrepaid'
        );

        $this->assertSame(
            $array['is_virtual'],
            $cc->isVirtual,
            'isVirtual'
        );

        $this->assertSame(
            $array['type'],
            $cc->type,
            'type'
        );

        $this->assertSame(
            $array,
            $cc->jsonSerialize(),
            'correctly implements JsonSerializable'
        );
    }
}
