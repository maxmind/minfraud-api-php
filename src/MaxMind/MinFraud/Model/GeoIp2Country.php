<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class GeoIp2Country
 * @package MaxMind\MinFraud\Model
 */
class GeoIp2Country extends \GeoIp2\Record\Country
{
    protected $validAttributes = array(
        'confidence',
        'geonameId',
        'isoCode',
        'isHighRisk',
        'names'
    );
}
