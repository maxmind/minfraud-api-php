<?php

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Country;

/**
 * Class GeoIp2Country
 * @package MaxMind\MinFraud\Model
 */
class GeoIp2Country extends Country
{
    protected $validAttributes = array(
        'confidence',
        'geonameId',
        'isoCode',
        'isHighRisk',
        'names'
    );
}
