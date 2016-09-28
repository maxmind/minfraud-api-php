<?php

namespace MaxMind\MinFraud\Model;

/**
 * Abstract model for a postal address.
 *
 * @property-read integer|null $distanceToIpLocation The distance in kilometers from
 * the address to the IP location.
 * @property-read boolean|null $isInIpCountry This property is true if the address
 * is in the IP country. The property is false when the address is not in the
 * IP country. If the address could not be parsed or was not provided or if
 * the IP address could not be geolocated, the property will be null.
 * @property-read boolean|null $isPostalInCity This property is true if the postal
 * code provided with the address is in the city for the address. The property
 * is false when the postal code is not in the city. If the address was not
 * provided, could not be parsed, or is outside USA, the property will be null.
 * @property-read float|null $latitude The latitude associated with the address.
 * @property-read float|null $longitude The longitude associated with the address.
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
