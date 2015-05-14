<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Address
 * @package MaxMind\MinFraud\Model
 *
 * @property integer $distanceToIpLocation The distance in kilometers from the
 * address to the IP location in kilometers.
 * @property boolean $isInIpCountry This field is true if the address is in the
 * IP country. The field is false when the address is not in the IP country. If
 * the address could not be parsed or was not provided of if the IP address
 * could not be geo-located, the field will not be included in the response.
 * @property boolean $isPostalInCity This field is true if the postal code
 * provided with the address is in the city for the address. The field is false
 * when the postal code is not in the city. If the address could not be parsed
 * or was not provided, the field will not be included in the response.
 * @property float $latitude The latitude associated with the address.
 * @property float $longitude The longitude associated with the address.
 *
 */
abstract class Address extends AbstractModel
{
    protected $isPostalInCity;
    protected $latitude;
    protected $longitude;
    protected $distanceToIpLocation;
    protected $isInIpCountry;

    /**
     *  * {@inheritdoc }
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->isPostalInCity = $this->get($response['is_postal_in_city']);
        $this->latitude = $this->get($response['latitude']);
        $this->longitude = $this->get($response['longitude']);
        $this->distanceToIpLocation = $this->get($response['distance_to_ip_location']);
        $this->isInIpCountry = $this->get($response['is_in_ip_country']);
    }
}
