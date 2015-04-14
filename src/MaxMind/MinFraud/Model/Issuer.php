<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Issuer
 * @package MaxMind\MinFraud\Model
 */
class Issuer extends AbstractModel
{
    protected $name;
    protected $matchesProvidedName;
    protected $phoneNumber;
    protected $matchesProvidedPhoneNumber;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->name = $this->get($response['name']);
        $this->matchesProvidedName
            = $this->get($response['matches_provided_name']);
        $this->phoneNumber = $this->get($response['phone_number']);
        $this->matchesProvidedNumber
            = $this->get($response['matches_provided_phone_number']);
    }
}
