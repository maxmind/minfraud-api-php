<?php

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Location;

/**
 * Class GeoIp2Location
 * @package MaxMind\MinFraud\Model
 */
class GeoIp2Location extends Location
{
    protected $validAttributes = array(
        'accuracyRadius',
        'latitude',
        'localTime',
        'longitude',
        'metroCode',
        'postalCode',
        'postalConfidence',
        'timeZone'
    );
}
