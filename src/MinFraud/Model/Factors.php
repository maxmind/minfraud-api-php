<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model representing the Factors response.
 */
class Factors extends Insights
{
    /**
     * @var array<RiskScoreReason> This array contains \MaxMind\MinFraud\Model\RiskScoreReason
     *                             objects that describe risk score reasons for a given transaction
     *                             that change the risk score significantly. Risk score reasons are
     *                             usually only returned for medium to high risk transactions.
     *                             If there were no significant changes to the risk score due to
     *                             these reasons, then this array will be empty.
     */
    public readonly array $riskScoreReasons;

    /**
     * @var Subscores an object containing scores for many of the individual
     *                risk factors that are used to calculate the overall risk
     *                score
     *
     * @deprecated use riskScoreReasons instead
     */
    public readonly Subscores $subscores;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $riskScoreReasons = [];
        if (isset($response['risk_score_reasons'])) {
            foreach ($response['risk_score_reasons'] as $reason) {
                $riskScoreReasons[] = new RiskScoreReason($reason);
            }
        }
        $this->riskScoreReasons = $riskScoreReasons;

        $this->subscores
            = new Subscores($response['subscores'] ?? []);
    }

    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();

        if (!empty($this->riskScoreReasons)) {
            $riskScoreReasons = [];
            foreach ($this->riskScoreReasons as $reason) {
                $riskScoreReasons[] = $reason->jsonSerialize();
            }
            $js['risk_score_reasons'] = $riskScoreReasons;
        }

        $subscores = $this->subscores->jsonSerialize();
        if (!empty($subscores)) {
            $js['subscores'] = $subscores;
        }

        return $js;
    }
}
