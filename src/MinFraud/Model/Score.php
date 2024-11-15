<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model of the Score response.
 */
class Score implements \JsonSerializable
{
    /**
     * @var Disposition an object containing the disposition set by custom
     *                  rules
     */
    public readonly Disposition $disposition;

    /**
     * @var float the approximate US dollar value of the funds remaining on
     *            your MaxMind account
     */
    public readonly float $fundsRemaining;

    /**
     * @var string This is a UUID that identifies the minFraud request. Please
     *             use this ID in bug reports or support requests to MaxMind
     *             so that we can easily identify a particular request.
     */
    public readonly string $id;

    /**
     * @var ScoreIpAddress an object containing the IP risk for the transaction
     */
    public readonly ScoreIpAddress $ipAddress;

    /**
     * @var int the approximate number of queries remaining for this service
     *          before your account runs out of funds
     */
    public readonly int $queriesRemaining;

    /**
     * @var float This property contains the risk score, from 0.01 to 99. A
     *            higher score indicates a higher risk of fraud. For example,
     *            a score of 20 indicates a 20% chance that a transaction is
     *            fraudulent. We never return a risk score of 0, since all
     *            transactions have the possibility of being fraudulent.
     *            Likewise we never return a risk score of 100.
     */
    public readonly float $riskScore;

    /**
     * @var array<Warning> This array contains \MaxMind\MinFraud\Model\Warning objects
     *                     detailing issues with the request that was sent, such as
     *                     invalid or unknown inputs. It is highly recommended that you
     *                     check this array for issues when integrating the web service.
     */
    public readonly array $warnings;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        $this->disposition
            = new Disposition($response['disposition'] ?? []);
        $this->fundsRemaining = $response['funds_remaining'];
        $this->queriesRemaining = $response['queries_remaining'];
        $this->id = $response['id'];
        $this->ipAddress
            = new ScoreIpAddress($response['ip_address'] ?? []);
        $this->riskScore = $response['risk_score'];

        $warnings = [];
        if (isset($response['warnings'])) {
            foreach ($response['warnings'] as $warning) {
                $warnings[] = new Warning($warning);
            }
        }
        $this->warnings = $warnings;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        $disposition = $this->disposition->jsonSerialize();
        if (!empty($disposition)) {
            $js['disposition'] = $disposition;
        }

        $js['funds_remaining'] = $this->fundsRemaining;

        $js['id'] = $this->id;

        $ipAddress = $this->ipAddress->jsonSerialize();
        if (!empty($ipAddress)) {
            $js['ip_address'] = $ipAddress;
        }

        $js['queries_remaining'] = $this->queriesRemaining;

        $js['risk_score'] = $this->riskScore;

        if (!empty($this->warnings)) {
            $warnings = [];
            foreach ($this->warnings as $warning) {
                $warnings[] = $warning->jsonSerialize();
            }
            $js['warnings'] = $warnings;
        }

        return $js;
    }
}
