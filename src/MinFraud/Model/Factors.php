<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Factors
 * @package MaxMind\MinFraud\Model
 *
 * @property \MaxMind\MinFraud\Model\Subscores subscores An object
 * containing subscores for many of the individual components that are
 * used to calculate the overall risk score.
 */
class Factors extends Insights
{
    /**
     * @internal
     */
    protected $subscores;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->subscores
            = new Subscores($this->safeArrayLookup($response['subscores']));
    }
}
