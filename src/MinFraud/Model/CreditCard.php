<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model with details about the credit card used.
 */
class CreditCard implements \JsonSerializable
{
    /**
     * @var string|null the card brand, such as "Visa", "Discover",
     *                  "American Express", etc
     */
    public readonly ?string $brand;

    /**
     * @var string|null This property contains the two letter
     *                  ISO 3166-1 alpha-2 country code
     *                  (https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) associated with the
     *                  location of the majority of customers using this credit card as determined
     *                  by their billing address. In cases where the location of customers is highly
     *                  mixed, this defaults to the country of the bank issuing the card.
     */
    public readonly ?string $country;

    /**
     * @var bool|null this property is true if the card is a
     *                business card
     */
    public readonly ?bool $isBusiness;

    /**
     * @var bool|null This property is
     *                true if the country of the billing address matches the country of the
     *                majority of customers using this credit card. In cases where the location
     *                of customers is highly mixed, the match is to the country of the bank
     *                issuing the card.
     */
    public readonly ?bool $isIssuedInBillingAddressCountry;

    /**
     * @var bool|null this property is true if the card is a
     *                prepaid card
     */
    public readonly ?bool $isPrepaid;

    /**
     * @var bool|null this property is true if the card is a
     *                virtual card
     */
    public readonly ?bool $isVirtual;

    /**
     * @var Issuer an object containing information about the credit card issuer
     */
    public readonly Issuer $issuer;

    /**
     * @var string|null The card's type. The valid values are: charge,
     *                  credit, debit.
     */
    public readonly ?string $type;

    public function __construct(?array $response)
    {
        $this->issuer = new Issuer($response['issuer'] ?? []);

        $this->brand = $response['brand'] ?? null;
        $this->country = $response['country'] ?? null;
        $this->isBusiness = $response['is_business'] ?? null;
        $this->isIssuedInBillingAddressCountry
            = $response['is_issued_in_billing_address_country'] ?? null;
        $this->isPrepaid = $response['is_prepaid'] ?? null;
        $this->isVirtual = $response['is_virtual'] ?? null;
        $this->type = $response['type'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];

        $issuer = $this->issuer->jsonSerialize();
        if (!empty($issuer)) {
            $js['issuer'] = $issuer;
        }

        if ($this->brand !== null) {
            $js['brand'] = $this->brand;
        }

        if ($this->country !== null) {
            $js['country'] = $this->country;
        }

        if ($this->isBusiness !== null) {
            $js['is_business'] = $this->isBusiness;
        }

        if ($this->isIssuedInBillingAddressCountry !== null) {
            $js['is_issued_in_billing_address_country'] = $this->isIssuedInBillingAddressCountry;
        }

        if ($this->isPrepaid !== null) {
            $js['is_prepaid'] = $this->isPrepaid;
        }

        if ($this->isVirtual !== null) {
            $js['is_virtual'] = $this->isVirtual;
        }

        if ($this->type !== null) {
            $js['type'] = $this->type;
        }

        return $js;
    }
}
