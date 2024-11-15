<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

use GeoIp2\Record\Location;

/**
 * Model of the GeoIP2 Location information, including the local time.
 */
class GeoIp2Location extends Location
{
    /**
     * @var string The date and time of the transaction in the time zone
     *             associated with the IP address. The value is formatted according to
     *             RFC 3339. For instance, the local time in Boston might be returned as
     *             2015-04-27T19:17:24-04:00.
     */
    public readonly ?string $localTime;

    /**
     * @param array<string, mixed> $record
     */
    public function __construct(array $record)
    {
        parent::__construct($record);

        $this->localTime = $record['local_time'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();

        if ($this->localTime !== null) {
            $js['local_time'] = $this->localTime;
        }

        return $js;
    }
}
