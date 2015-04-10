<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Score
 * @package MaxMind\MinFraud\Model
 */
class Score extends AbstractModel
{
    protected $creditsRemaining;
    protected $id;
    protected $riskScore;
    protected $warnings;

    /**
     * @param array $response
     * @param array $locales
     */
    public function __construct($response, $locales = array('en'))
    {
        $this->creditsRemaining = $this->get($response['credits_remaining']);
        $this->id = $this->get($response['id']);
        $this->riskScore = $this->get($response['risk_score']);

        $this->warnings = array();
        foreach ($this->get($response['warnings'], array()) as $warning) {
            array_push($this->warnings, new Warning($warning));
        }
    }

    /**
     * @return int
     */
    public function creditsRemaining()
    {
        return $this->creditsRemaining;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function riskScore()
    {
        return $this->riskScore;
    }
}
