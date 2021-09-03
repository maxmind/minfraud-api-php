<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model with information about the device.
 *
 * In order to receive device output from minFraud Insights or minFraud
 * Factors, you must be using the Device Tracking Add-on.
 *
 * @link https://dev.maxmind.com/minfraud/track-devices?lang=en Device Tracking
 * Add-on
 *
 * @property-read float|null $confidence This number represents our confidence that
 * the `device_id` refers to a unique device as opposed to a cluster of
 * similar devices. A confidence of 0.01 indicates very low confidence that
 * the device is unique, whereas 99 indicates very high confidence.
 * @property-read string|null $id A UUID that MaxMind uses for the device associated
 * with this IP address. Note that many devices cannot be uniquely identified
 * because they are too common (for example, all iPhones of a given model and
 * OS release). In these cases, the minFraud service will simply not return a
 * UUID for that device.
 * @property-read string|null $lastSeen This is the date and time of the last
 * sighting of the device. This is an RFC 3339 date-time.
 * @property-read string|null $localTime This is the local date and time of
 * the transaction in the time zone of the device. This is determined by using
 * the UTC offset associated with the device. This is an RFC 3339 date-time
 */
class Device extends AbstractModel
{
    /**
     * @internal
     *
     * @var float|null
     */
    protected $confidence;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $id;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $lastSeen;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $localTime;

    public function __construct(?array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->confidence = $this->safeArrayLookup($response['confidence']);
        $this->id = $this->safeArrayLookup($response['id']);
        $this->lastSeen = $this->safeArrayLookup($response['last_seen']);
        $this->localTime = $this->safeArrayLookup($response['local_time']);
    }
}
