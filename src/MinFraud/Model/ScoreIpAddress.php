<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing the IP address's risk for the Score response.
 *
 * @property-read float $risk This field contains the risk associated with the IP
 * address. The value ranges from 0.01 to 99. A higher score indicates a
 * higher risk.
 */
class ScoreIpAddress extends AbstractModel
{
    /**
     * @ignore
     *
     * @var float
     */
    protected $risk;

    public function __construct(?array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->risk = $this->safeArrayLookup($response['risk']);
    }
}
