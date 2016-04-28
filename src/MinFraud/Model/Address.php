<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Address
 * @package MaxMind\MinFraud\Model
 *
 * @property integer|null $distanceToIpLocation The distance in kilometers from
 * the address to the IP location.
 * @property boolean|null $isInIpCountry This property is true if the address
 * is in the IP country. The property is false when the address is not in the
 * IP country. If the address could not be parsed or was not provided or if
 * the IP address could not be geolocated, the property will be null.
 * @property boolean|null $isPostalInCity This property is true if the postal
 * code provided with the address is in the city for the address. The property
 * is false when the postal code is not in the city. If the address could not
 * be parsed or was not provided, the property will be null.
 * @property float|null $latitude The latitude associated with the address.
 * @property float|null $longitude The longitude associated with the address.
 *
 */
abstract class Address extends AbstractModel
{
    /**
     * @internal
     */
    protected $isPostalInCity;

    /**
     * @internal
     */
    protected $latitude;

    /**
     * @internal
     */
    protected $longitude;

    /**
     * @internal
     */
    protected $distanceToIpLocation;

    /**
     * @internal
     */
    protected $isInIpCountry;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->isPostalInCity = $this->safeArrayLookup($response['is_postal_in_city']);
        $this->latitude = $this->safeArrayLookup($response['latitude']);
        $this->longitude = $this->safeArrayLookup($response['longitude']);
        $this->distanceToIpLocation = $this->safeArrayLookup($response['distance_to_ip_location']);
        $this->isInIpCountry = $this->safeArrayLookup($response['is_in_ip_country']);
    }
}
