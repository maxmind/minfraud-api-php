<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class CreditCard
 * @package MaxMind\MinFraud\Model
 *
 * @property string $brand The card brand, such as "Visa", "Discover",
 * "American Express", etc.
 *
 * @property string $country This property contains the the two letter {@link
 * http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2 ISO 3166-1 alpha-2 country
 * code} associated with the location of the majority of customers using this
 * credit card as determined by their billing address. In cases where the
 * location of customers is highly mixed, this defaults to the country of the
 * bank issuing the card.
 *
 * @property boolean $isIssuedInBillingAddressCountry This property is true if
 * the country of the billing address matches the country of the majority of
 * customers using this credit card. In cases where the location of customers
 * is highly mixed, the match is to the country of the bank issuing the card.
 *
 * @property boolean $isPrepaid This property is true if the card is a prepaid
 * card.
 *
 * @property \MaxMind\MinFraud\Model\Issuer $issuer An object containing
 * information about the credit card issuer.
 *
 * @property string $type The card's type. The valid values are: charge,
 * credit, debit.
 */
class CreditCard extends AbstractModel
{
    /**
     * @internal
     */
    protected $brand;

    /**
     * @internal
     */
    protected $country;

    /**
     * @internal
     */
    protected $isIssuedInBillingAddressCountry;

    /**
     * @internal
     */
    protected $isPrepaid;

    /**
     * @internal
     */
    protected $issuer;

    /**
     * @internal
     */
    protected $type;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->issuer = new Issuer($this->safeArrayLookup($response['issuer']));

        $this->brand = $this->safeArrayLookup($response['brand']);
        $this->country = $this->safeArrayLookup($response['country']);
        $this->isIssuedInBillingAddressCountry
            = $this->safeArrayLookup($response['is_issued_in_billing_address_country']);
        $this->isPrepaid = $this->safeArrayLookup($response['is_prepaid']);
        $this->type = $this->safeArrayLookup($response['type']);
    }
}
