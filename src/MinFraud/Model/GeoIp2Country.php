<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Country;

/**
 * Model of the GeoIP2 country information.
 *
 * @property-read bool $isHighRisk This value is true if the IP country is high
 * risk. <b>Deprecated effective August 29, 2019.</b>
 */
class GeoIp2Country extends Country
{
    /**
     * @internal
     *
     * @var array<string>
     */
    protected $validAttributes = [
        'confidence',
        'geonameId',
        'isInEuropeanUnion',
        'isoCode',

        // @deprecated
        'isHighRisk',
        'names',
    ];
}
