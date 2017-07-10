<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model with information about the device.
 *
 * In order to receive device output from minFraud Insights or minFraud
 * Factors, you must be using the Device Tracking Add-on.
 *
 * @link https://dev.maxmind.com/minfraud/device/ Device Tracking Add-on
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
 */
class Device extends AbstractModel
{
    /**
     * @internal
     */
    protected $confidence;

    /**
     * @internal
     */
    protected $id;

    /**
     * @internal
     */
    protected $lastSeen;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->confidence = $this->safeArrayLookup($response['confidence']);
        $this->id = $this->safeArrayLookup($response['id']);
        $this->lastSeen = $this->safeArrayLookup($response['last_seen']);
    }
}
