<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class GeoIp2Location
 * @package MaxMind\MinFraud\Model
 */
class GeoIp2Location extends \GeoIp2\Record\Location
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
