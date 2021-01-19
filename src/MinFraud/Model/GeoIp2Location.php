<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Location;

/**
 * Model of the GeoIP2 Location information, including the local time.
 *
 * @property-read string $localTime The date and time of the transaction in the time
 * zone associated with the IP address. The value is formatted according to RFC
 * 3339. For instance, the local time in Boston might be returned as
 * 2015-04-27T19:17:24-04:00.
 */
class GeoIp2Location extends Location
{
    /**
     * @internal
     *
     * @var array<string>
     */
    protected $validAttributes = [
        'accuracyRadius',
        'latitude',
        'localTime',
        'longitude',
        'metroCode',
        'postalCode',
        'postalConfidence',
        'timeZone',
    ];
}
