<?php

namespace MaxMind\MinFraud\Model;

/**
 * Class Score
 * @package MaxMind\MinFraud\Model
 *
 * @property integer $creditsRemaining The approximate number of service credits
 * remaining on your account.
 * @property string $id This is a UUID that identifies the minFraud request.
 * Please use this ID in bug reports or support requests to MaxMind so that we
 * can easily identify a particular request.
 * @property float $riskScore This field contains the risk score, from 0.01 to
 * 99. A higher score indicates a higher risk of fraud. For example, a score of
 * 20 indicates a 20% chance that a transaction is fraudulent. We never return a
 * risk score of 0, since all transactions have the possibility of being
 * fraudulent. Likewise we never return a risk score of 100.
 * @property array $warnings This array contains
 * {@link \MaxMind\MinFraud\Model\Warning Warning} objects detailing issues with
 * the request that was sent such as invalid or unknown inputs. It is highly
 * recommended that you check this array for issues when integrating the web
 * service.
 */
class Score extends AbstractModel
{
    protected $creditsRemaining;
    protected $id;
    protected $riskScore;
    protected $warnings;

    /**
     * {@inheritdoc }
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
}
