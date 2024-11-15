<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the card issuer.
 */
class Issuer implements \JsonSerializable
{
    /**
     * @var string|null the name of the bank which issued the credit card
     */
    public readonly ?string $name;

    /**
     * @var bool|null This property is true if the name
     *                matches the name provided in the request for the card issuer. It is false
     *                if the name does not match. The property is null if either no name or issuer
     *                ID number (IIN) was provided in the request or if MaxMind does not have a
     *                name associated with the IIN.
     */
    public readonly ?bool $matchesProvidedName;

    /**
     * @var string|null The phone number of the bank which issued
     *                  the credit card. In some cases the phone number we return may be out of date.
     */
    public readonly ?string $phoneNumber;

    /**
     * @var bool|null This property is true if
     *                the phone number matches the number provided in the request for the card
     *                issuer. It is false if the number does not match. It is null if either no
     *                phone number was provided or issuer ID number (IIN) was provided in the
     *                request or if MaxMind does not have a phone number associated with the IIN.
     */
    public readonly ?bool $matchesProvidedPhoneNumber;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->name = $response['name'] ?? null;
        $this->matchesProvidedName
            = $response['matches_provided_name'] ?? null;
        $this->phoneNumber = $response['phone_number'] ?? null;
        $this->matchesProvidedPhoneNumber
            = $response['matches_provided_phone_number'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->name !== null) {
            $js['name'] = $this->name;
        }

        if ($this->matchesProvidedName !== null) {
            $js['matches_provided_name'] = $this->matchesProvidedName;
        }

        if ($this->phoneNumber !== null) {
            $js['phone_number'] = $this->phoneNumber;
        }

        if ($this->matchesProvidedPhoneNumber !== null) {
            $js['matches_provided_phone_number'] = $this->matchesProvidedPhoneNumber;
        }

        return $js;
    }
}
