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
        $this->isPostalInCity = $this->get($response['is_postal_in_city']);
        $this->latitude = $this->get($response['latitude']);
        $this->longitude = $this->get($response['longitude']);
        $this->distanceToIpLocation = $this->get($response['distance_to_ip_location']);
        $this->isInIpCountry = $this->get($response['is_in_ip_country']);
    }

    /**
     * @return string
     */
    public function isPostalInCity()
    {
        return $this->isPostalInCity;
    }

    /**
     * @return float
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * @return int
     */
    public function distanceToIpLocation()
    {
        return $this->distanceToIpLocation;
    }

    /**
     * @return boolean
     */
    public function isInIpCountry()
    {
        return $this->isInIpCountry;
    }
}
