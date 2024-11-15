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
 */
class Device implements \JsonSerializable
{
    /**
     * @var float|null This number represents our confidence that
     *                 the `device_id` refers to a unique device as opposed to a cluster of
     *                 similar devices. A confidence of 0.01 indicates very low confidence that
     *                 the device is unique, whereas 99 indicates very high confidence.
     */
    public readonly ?float $confidence;

    /**
     * @var string|null a UUID that MaxMind uses for the device associated
     *                  with this IP address
     */
    public readonly ?string $id;

    /**
     * @var string|null This is the date and time of the last
     *                  sighting of the device. This is an RFC 3339 date-time.
     */
    public readonly ?string $lastSeen;

    /**
     * @var string|null This is the local date and time of
     *                  the transaction in the time zone of the device. This is determined by using
     *                  the UTC offset associated with the device. This is an RFC 3339 date-time
     */
    public readonly ?string $localTime;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->confidence = $response['confidence'] ?? null;
        $this->id = $response['id'] ?? null;
        $this->lastSeen = $response['last_seen'] ?? null;
        $this->localTime = $response['local_time'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->confidence !== null) {
            $js['confidence'] = $this->confidence;
        }

        if ($this->id !== null) {
            $js['id'] = $this->id;
        }

        if ($this->lastSeen !== null) {
            $js['last_seen'] = $this->lastSeen;
        }

        if ($this->localTime !== null) {
            $js['local_time'] = $this->localTime;
        }

        return $js;
    }
}
