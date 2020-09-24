<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Model\Insights as GeoIp2Insights;

/**
 * Model containing GeoIP2 data and the risk for the IP address.
 *
 * @property-read float $risk This field contains the risk associated with the IP
 * address. The value ranges from 0.01 to 99. A higher score indicates a
 * higher risk.
 */
class IpAddress extends GeoIp2Insights
{
    /**
     * @ignore
     */
    protected $risk;

    public function __construct(?array $response, array $locales = ['en'])
    {
        if ($response === null) {
            $response = [];
        }
        parent::__construct($response, $locales);
        $this->country = new GeoIp2Country($this->get('country'), $locales);
        $this->location = new GeoIp2Location($this->get('location'));
        $this->risk = $this->get('risk');
    }
}
