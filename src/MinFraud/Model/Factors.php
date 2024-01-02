<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model representing the Factors response.
 */
class Factors extends Insights
{
    /**
     * @var Subscores an object
     *                containing scores for many of the individual risk factors that are used to
     *                calculate the overall risk score
     */
    public readonly Subscores $subscores;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->subscores
            = new Subscores($response['subscores'] ?? []);
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();

        $subscores = $this->subscores->jsonSerialize();
        if (!empty($subscores)) {
            $js['subscores'] = $subscores;
        }

        return $js;
    }
}
