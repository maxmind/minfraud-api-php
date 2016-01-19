<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Device
 * @package MaxMind\MinFraud\Model
 *
 * @property string $id A UUID that MaxMind uses for the device associated
 * with this IP address. Note that many devices cannot be uniquely identified
 * because they are too common (for example, all iPhones of a given model and
 * OS release). In these cases, the minFraud service will simply not return a
 * UUID for that device.
 *
 */
class Device extends AbstractModel
{
    /**
     * @internal
     */
    protected $id;

    public function __construct($response, $locales = ['en'])
    {
        $this->id = $this->safeArrayLookup($response['id']);
    }
}
