<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model of the Score response.
 *
 * @property-read float $fundsRemaining The approximate US dollar value of the
 * funds remaining on your MaxMind account.
 * @property-read int $queriesRemaining The approximate number of queries
 * remaining for this service before your account runs out of funds.
 * @property-read int $rawResponse The raw data that comes back from the post
 * request to the maxmind server.
 * @property-read string $id This is a UUID that identifies the minFraud request.
 * Please use this ID in bug reports or support requests to MaxMind so that we
 * can easily identify a particular request.
 * @property-read float $riskScore This property contains the risk score, from 0.01
 * to 99. A higher score indicates a higher risk of fraud. For example, a
 * score of 20 indicates a 20% chance that a transaction is fraudulent. We
 * never return a risk score of 0, since all transactions have the possibility
 * of being fraudulent. Likewise we never return a risk score of 100.
 * @property-read \MaxMind\MinFraud\Model\Disposition $disposition An object
 * containing the disposition set by custom rules.
 * @property-read \MaxMind\MinFraud\Model\ScoreIpAddress $ipAddress An object
 * containing the IP risk for the transaction.
 * @property-read array $warnings This array contains
 * \MaxMind\MinFraud\Model\Warning objects detailing issues with the request
 * that was sent, such as invalid or unknown inputs. It is highly recommended
 * that you check this array for issues when integrating the web service.
 */
class Score extends AbstractModel
{
    /**
     * @internal
     *
     * @var Disposition
     */
    protected $disposition;

    /**
     * @internal
     *
     * @var float
     */
    protected $fundsRemaining;

    /**
     * @internal
     *
     * @var string
     */
    protected $id;

    /**
     * @internal
     *
     * @var ScoreIpAddress
     */
    protected $ipAddress;

    /**
     * @internal
     *
     * @var int
     */
    protected $queriesRemaining;

    /**
     * @internal
     *
     * @var array
     */
    protected $rawResponse;

    /**
     * @internal
     *
     * @var float
     */
    protected $riskScore;

    /**
     * @internal
     *
     * @var array<Warning>
     */
    protected $warnings;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->disposition
            = new Disposition($this->safeArrayLookup($response['disposition']));
        $this->fundsRemaining = $this->safeArrayLookup($response['funds_remaining']);
        $this->queriesRemaining = $this->safeArrayLookup($response['queries_remaining']);
        $this->id = $this->safeArrayLookup($response['id']);
        $this->ipAddress
            = new ScoreIpAddress($this->safeArrayLookup($response['ip_address']));
        $this->riskScore = $this->safeArrayLookup($response['risk_score']);

        $this->warnings = [];
        foreach ($this->safeArrayLookup($response['warnings'], []) as $warning) {
            $this->warnings[] = new Warning($warning);
        }
    }
}
