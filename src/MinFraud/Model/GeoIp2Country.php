<?php

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Country;

/**
 * Model of the GeoIP2 country information.
 *
 * @property-read bool $isHighRisk This value is true if the IP country is high
 * risk.
 **/
class GeoIp2Country extends Country
{
    /**
     * @internal
     */
    protected $validAttributes = [
        'confidence',
        'geonameId',
        'isInEuropeanUnion',
        'isoCode',
        'isHighRisk',
        'names',
    ];
}
