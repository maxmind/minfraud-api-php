<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model representing the Factors response.
 *
 * @property-read \MaxMind\MinFraud\Model\Subscores $subscores An object
 * containing scores for many of the individual risk factors that are used to
 * calculate the overall risk score.
 */
class Factors extends Insights
{
    /**
     * @internal
     *
     * @var Subscores
     */
    protected $subscores;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->subscores
            = new Subscores($this->safeArrayLookup($response['subscores']));
    }
}
