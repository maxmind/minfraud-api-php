<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Address
 * @package MaxMind\MinFraud\Model
 */
abstract class Address extends AbstractModel
{
    protected $isPostalInCity;
    protected $latitude;
    protected $longitude;
    protected $distanceToIpLocation;
    protected $isInIpCountry;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->isPostalInCity = $this->safeArrayLookup($response['is_postal_in_city']);
        $this->latitude = $this->safeArrayLookup($response['latitude']);
        $this->longitude = $this->safeArrayLookup($response['longitude']);
        $this->distanceToIpLocation = $this->safeArrayLookup($response['distance_to_ip_location']);
        $this->isInIpCountry = $this->safeArrayLookup($response['is_in_ip_country']);
    }
}
