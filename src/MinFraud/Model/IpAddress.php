<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Model\Insights;
use GeoIp2\Record\City;
use GeoIp2\Record\Continent;
use GeoIp2\Record\Country;
use GeoIp2\Record\Postal;
use GeoIp2\Record\RepresentedCountry;
use GeoIp2\Record\Subdivision;
use GeoIp2\Record\Traits;

/**
 * Model containing GeoIP2 data and the risk for the IP address.
 */
class IpAddress implements \JsonSerializable
{
    /**
     * @var City city data for the requested IP address
     */
    public readonly City $city;

    /**
     * @var Continent continent data for the requested IP address
     */
    public readonly Continent $continent;

    /**
     * @var Country Country data for the requested IP address. This object
     *              represents the country where MaxMind believes the end
     *              user is located.
     */
    public readonly Country $country;

    /**
     * @var Country Registered country data for the requested IP address.
     *              This record represents the country where the ISP has
     *              registered a given IP block and may differ from the
     *              user's country.
     */
    public readonly Country $registeredCountry;

    /**
     * @var GeoIp2Location location data for the requested IP address
     */
    public readonly GeoIp2Location $location;

    /**
     * @var Subdivision An object representing the most specific subdivision
     *                  returned. If the response did not contain any
     *                  subdivisions, this method returns an empty
     *                  \GeoIp2\Record\Subdivision object.
     */
    public readonly Subdivision $mostSpecificSubdivision;

    /**
     * @var Postal postal data for the requested IP address
     */
    public readonly Postal $postal;

    /**
     * @var RepresentedCountry Represented country data for the requested IP
     *                         address. The represented country is used for
     *                         things like military bases. It is only present
     *                         when the represented country differs from the
     *                         country.
     */
    public readonly RepresentedCountry $representedCountry;

    /**
     * @var float|null This field contains the risk associated with the IP
     *                 address. The value ranges from 0.01 to 99. A higher
     *                 score indicates a higher risk.
     */
    public readonly ?float $risk;

    /**
     * @var array<IpRiskReason> This array contains
     *                          \MaxMind\MinFraud\Model\IpRiskReason objects
     *                          identifying the reasons why the IP address
     *                          received the associated risk. This will be an
     *                          empty array if there are no reasons.
     */
    public readonly array $riskReasons;

    /**
     * @var array<Subdivision> An array of \GeoIp2\Record\Subdivision objects representing
     *                         the country subdivisions for the requested IP address. The
     *                         number and type of subdivisions varies by country, but a
     *                         subdivision is typically a state, province, county, etc.
     *                         Subdivisions are ordered from most general (largest) to most
     *                         specific (smallest). If the response did not contain any
     *                         subdivisions, this method returns an empty array.
     */
    public readonly array $subdivisions;

    /**
     * @var Traits data for the traits of the requested IP
     *             address
     */
    public readonly Traits $traits;

    /**
     * @param array<string, mixed>|null $response
     * @param list<string>              $locales
     */
    public function __construct(?array $response, array $locales = ['en'])
    {
        if ($response === null) {
            $response = [];
        }

        $insights = new Insights($response, $locales);
        $this->city = $insights->city;
        $this->continent = $insights->continent;
        $this->country = $insights->country;
        $this->mostSpecificSubdivision = $insights->mostSpecificSubdivision;
        $this->postal = $insights->postal;
        $this->registeredCountry = $insights->registeredCountry;
        $this->representedCountry = $insights->representedCountry;
        $this->subdivisions = $insights->subdivisions;
        $this->traits = $insights->traits;

        $this->location = new GeoIp2Location($response['location'] ?? []);
        $this->risk = $response['risk'] ?? null;

        $riskReasons = [];
        if (isset($response['risk_reasons'])) {
            foreach ($response['risk_reasons'] as $reason) {
                $riskReasons[] = new IpRiskReason($reason);
            }
        }
        $this->riskReasons = $riskReasons;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): ?array
    {
        $js = [];

        $city = $this->city->jsonSerialize();
        if (!empty($city)) {
            $js['city'] = $city;
        }

        $continent = $this->continent->jsonSerialize();
        if (!empty($continent)) {
            $js['continent'] = $continent;
        }

        $country = $this->country->jsonSerialize();
        if (!empty($country)) {
            $js['country'] = $country;
        }

        $location = $this->location->jsonSerialize();
        if (!empty($location)) {
            $js['location'] = $location;
        }

        $registeredCountry = $this->registeredCountry->jsonSerialize();
        if (!empty($registeredCountry)) {
            $js['registered_country'] = $registeredCountry;
        }

        $representedCountry = $this->representedCountry->jsonSerialize();
        if (!empty($representedCountry)) {
            $js['represented_country'] = $representedCountry;
        }

        $traits = $this->traits->jsonSerialize();
        if (!empty($traits)) {
            $js['traits'] = $traits;
        }

        $postal = $this->postal->jsonSerialize();
        if (!empty($postal)) {
            $js['postal'] = $postal;
        }

        if ($this->risk !== null) {
            $js['risk'] = $this->risk;
        }

        if (!empty($this->riskReasons)) {
            $reasons = [];
            foreach ($this->riskReasons as $reason) {
                $reasons[] = $reason->jsonSerialize();
            }
            $js['risk_reasons'] = $reasons;
        }

        $subdivisions = [];
        foreach ($this->subdivisions as $sub) {
            $subdivisions[] = $sub->jsonSerialize();
        }
        if (!empty($subdivisions)) {
            $js['subdivisions'] = $subdivisions;
        }

        return $js;
    }
}
