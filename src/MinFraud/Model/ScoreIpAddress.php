<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class ScoreIpAddress
 * @package MaxMind\MinFraud\Model
 *
 * @property float $risk This field contains the risk associated with the IP
 * address. The value ranges from 0.01 to 99. A higher score indicates a
 * higher risk.
 */
class ScoreIpAddress extends AbstractModel
{
    /**
     * @ignore
     */
    protected $risk;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->risk = $this->safeArrayLookup($response['risk']);
    }
}
