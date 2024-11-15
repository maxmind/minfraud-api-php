<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the billing or shipping phone number.
 */
class Phone implements \JsonSerializable
{
    /**
     * @var string|null the two-character ISO 3166-1 country code for the
     *                  country associated with the phone number
     */
    public readonly ?string $country;

    /**
     * @var bool|null This is `true` if the phone number is a Voice over
     *                Internet Protocol (VoIP) number allocated by a regulator.
     *                It is `false` if the phone number is not a VoIP number
     *                allocated by a regulator. It is `null` if a valid number
     *                was not provided or if we do not have data for the number.
     */
    public readonly ?bool $isVoip;

    /**
     * @var string|null The name of the original network operator associated with
     *                  the phone number. This property does not reflect phone numbers
     *                  that have been ported from the original operator to another,
     *                  nor does it identify mobile virtual network operators.
     */
    public readonly ?string $networkOperator;

    /**
     * @var string|null One of the following values: `fixed` or `mobile`. Additional
     *                  values may be added in the future.
     */
    public readonly ?string $numberType;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->country = $response['country'] ?? null;
        $this->isVoip = $response['is_voip'] ?? null;
        $this->networkOperator = $response['network_operator'] ?? null;
        $this->numberType = $response['number_type'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->country !== null) {
            $js['country'] = $this->country;
        }

        if ($this->isVoip !== null) {
            $js['is_voip'] = $this->isVoip;
        }

        if ($this->networkOperator !== null) {
            $js['network_operator'] = $this->networkOperator;
        }

        if ($this->numberType !== null) {
            $js['number_type'] = $this->numberType;
        }

        return $js;
    }
}
