<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Model\Insights as GeoIp2Insights;

/**
 * Model containing GeoIP2 data and the risk for the IP address.
 *
 * @property-read \MaxMind\MinFraud\Model\GeoIp2Country $country Country data
 * for the requested IP address. This object represents the country where MaxMind
 * believes the end user is located.
 * @property-read \MaxMind\MinFraud\Model\GeoIp2Location $location Location data
 * for the requested IP address.
 * @property-read float|null $risk This field contains the risk associated with the IP
 * address. The value ranges from 0.01 to 99. A higher score indicates a
 * higher risk.
 */
class IpAddress extends GeoIp2Insights
{
    /**
     * @ignore
     *
     * @var float|null
     */
    protected $risk;

    public function __construct(?array $response, array $locales = ['en'])
    {
        if ($response === null) {
            $response = [];
        }
        parent::__construct($response, $locales);
        // @phpstan-ignore-next-line
        $this->country = new GeoIp2Country($this->get('country'), $locales);
        // @phpstan-ignore-next-line
        $this->location = new GeoIp2Location($this->get('location'));
        $this->risk = $this->get('risk');
    }
}
