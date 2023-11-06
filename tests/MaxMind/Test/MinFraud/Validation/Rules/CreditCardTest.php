<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Validation\Rules;

use MaxMind\MinFraud\Validation\Rules\CreditCard;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 *
 * @internal
 */
class CreditCardTest extends TestCase
{
    /**
     * @dataProvider invalidCountries
     *
     * @param mixed $code
     */
    public function testInvalidCountry($code): void
    {
        $validator = new CreditCard();

        $this->expectException(\Respect\Validation\Exceptions\CountryCodeException::class);

        $validator->check([
            'country' => $code,
        ]);
    }

    public static function invalidCountries(): array
    {
        return [
            ['USA'],
            ['Canada'],
            [1],
            [null],
            ['ca'],
        ];
    }

    /**
     * @dataProvider validCountries
     */
    public function testValidCountry(string $code): void
    {
        $validator = new CreditCard();

        $this->assertTrue(
            $validator->validate([
                'country' => $code,
            ]),
            $code,
        );
    }

    public static function validCountries(): array
    {
        return [
            ['US'],
            ['CA'],
        ];
    }
}
