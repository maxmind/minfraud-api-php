<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing the IP address's risk for the Score response.
 */
class ScoreIpAddress implements \JsonSerializable
{
    /**
     * @var float|null This field contains the risk associated with the IP
     *                 address. The value ranges from 0.01 to 99. A higher score indicates a
     *                 higher risk.
     */
    public readonly ?float $risk;

    public function __construct(?array $response)
    {
        $this->risk = $response['risk'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->risk !== null) {
            $js['risk'] = $this->risk;
        }

        return $js;
    }
}
