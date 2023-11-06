<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Abstract model for a postal address.
 */
abstract class Address implements \JsonSerializable
{
    /**
     * @var int|null the distance in kilometers from
     *               the address to the IP location
     */
    public readonly ?int $distanceToIpLocation;

    /**
     * @var bool|null This property is true if the address is in the IP
     *                country. The property is false when the address is not in the IP
     *                country. If the address could not be parsed or was not provided or if
     *                the IP address could not be geolocated, the property will be null.
     */
    public readonly ?bool $isInIpCountry;

    /**
     * @var bool|null This property is true if the postal code provided with
     *                the address is in the city for the address. The property is false when
     *                the postal code is not in the city. If the address was not provided or
     *                could not be parsed, the property will be null.
     */
    public readonly ?bool $isPostalInCity;

    /**
     * @var float|null the latitude associated with the address
     */
    public readonly ?float $latitude;

    /**
     * @var float|null the longitude associated with the address
     */
    public readonly ?float $longitude;

    public function __construct(?array $response)
    {
        $this->distanceToIpLocation = $response['distance_to_ip_location'] ?? null;
        $this->isInIpCountry = $response['is_in_ip_country'] ?? null;
        $this->isPostalInCity = $response['is_postal_in_city'] ?? null;
        $this->latitude = $response['latitude'] ?? null;
        $this->longitude = $response['longitude'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->distanceToIpLocation !== null) {
            $js['distance_to_ip_location'] = $this->distanceToIpLocation;
        }
        if ($this->isInIpCountry !== null) {
            $js['is_in_ip_country'] = $this->isInIpCountry;
        }
        if ($this->isPostalInCity !== null) {
            $js['is_postal_in_city'] = $this->isPostalInCity;
        }
        if ($this->latitude !== null) {
            $js['latitude'] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $js['longitude'] = $this->longitude;
        }

        return $js;
    }
}
