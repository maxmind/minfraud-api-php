<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * The risk score multiplier and the reasons for that multiplier.
 */
class RiskScoreReason implements \JsonSerializable
{
    /**
     * @var float|null The factor by which the risk score is increased (if the value is greater than 1)
     *                 or decreased (if the value is less than 1) for given risk reason(s).
     *                 Multipliers greater than 1.5 and less than 0.66 are considered significant
     *                 and lead to risk reason(s) being present.
     */
    public readonly ?float $multiplier;

    /**
     * @var array<Reason> This array contains \MaxMind\MinFraud\Model\Reason objects that describe
     *                    one of the reasons for the multiplier
     */
    public readonly array $reasons;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        if ($response === null) {
            $response = [];
        }

        $this->multiplier = $response['multiplier'] ?? null;

        $reasons = [];
        if (isset($response['reasons'])) {
            foreach ($response['reasons'] as $reason) {
                $reasons[] = new Reason($reason);
            }
        }
        $this->reasons = $reasons;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): ?array
    {
        $js = [];

        if ($this->multiplier !== null) {
            $js['multiplier'] = $this->multiplier;
        }

        if (!empty($this->reasons)) {
            $reasons = [];
            foreach ($this->reasons as $reason) {
                $reasons[] = $reason->jsonSerialize();
            }
            $js['reasons'] = $reasons;
        }

        return $js;
    }
}
