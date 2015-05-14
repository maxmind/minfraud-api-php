<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Issuer
 * @package MaxMind\MinFraud\Model
 *
 * @property string $name The name of the bank which issued the credit card.
 * @property boolean $matchesProvidedName This field is true if the name matches
 * the name provided in the request for the card issuer. It is false if the name
 * does not match. The field is not included if either no name or issuer ID
 * number (IIN) was provided in the request or if MaxMind does not have a name
 * associated with the IIN.
 * @property string $phoneNumber The phone number of the bank which issued the
 * credit card. In some cases the phone number we return may be out of date.
 * @property boolean $matchesProvidedPhoneNumber This field is true if the phone
 * number matches the number provided in the request for the card issuer. It is
 * false if the number does not match. The field is not included if either no
 * phone number or issuer ID number (IIN) was provided in the request or if
 * MaxMind does not have a phone number associated with the IIN.
 */
class Issuer extends AbstractModel
{
    protected $name;
    protected $matchesProvidedName;
    protected $phoneNumber;
    protected $matchesProvidedPhoneNumber;

    /**
     * {@inheritdoc }
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->name = $this->get($response['name']);
        $this->matchesProvidedName
            = $this->get($response['matches_provided_name']);
        $this->phoneNumber = $this->get($response['phone_number']);
        $this->matchesProvidedPhoneNumber
            = $this->get($response['matches_provided_phone_number']);
    }
}
