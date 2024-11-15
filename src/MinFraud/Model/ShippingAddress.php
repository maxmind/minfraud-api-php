<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing properties of the shipping address.
 */
class ShippingAddress extends Address
{
    /**
     * @var int|null the distance in kilometers
     *               from the shipping address to billing address
     */
    public readonly ?int $distanceToBillingAddress;

    /**
     * @var bool|null This property is true if the shipping
     *                address is an address associated with fraudulent transactions. The property
     *                is false when the address is not associated with increased risk. The
     *                property will be `null` when a shipping address is not provided.
     */
    public readonly ?bool $isHighRisk;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        parent::__construct($response);
        $this->isHighRisk = $response['is_high_risk'] ?? null;
        $this->distanceToBillingAddress
            = $response['distance_to_billing_address'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();

        if ($this->distanceToBillingAddress !== null) {
            $js['distance_to_billing_address'] = $this->distanceToBillingAddress;
        }

        if ($this->isHighRisk !== null) {
            $js['is_high_risk'] = $this->isHighRisk;
        }

        return $js;
    }
}
