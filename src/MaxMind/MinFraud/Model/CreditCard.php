<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class CreditCard
 * @package MaxMind\MinFraud\Model
 */
class CreditCard extends AbstractModel
{
    protected $issuer;
    protected $country;
    protected $isIssuedInBillingAddressCountry;
    protected $isPrepaid;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->issuer = new Issuer($this->get($response['issuer']));
        $this->country = $this->get($response['country']);
        $this->isIssuedInBillingAddressCountry
            = $this->get($response['is_issued_in_billing_address_country']);
        $this->isPrepaid = $this->get($response['is_prepaid']);
    }

    /**
     * @return Issuer
     */
    public function issuer()
    {
        return $this->issuer;
    }

    /**
     * @return string
     */
    public function country()
    {
        return $this->country;
    }

    /**
     * @return boolean
     */
    public function isIssuedInBillingAddressCountry()
    {
        return $this->isIssuedInBillingAddressCountry;
    }

    /**
     * @return boolean
     */
    public function isPrepaid()
    {
        return $this->isPrepaid;
    }
}
