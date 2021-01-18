<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing properties of the shipping address.
 *
 * @property-read int|null $distanceToBillingAddress The distance in kilometers
 * from the shipping address to billing address.
 * @property-read bool|null $isHighRisk This property is true if the shipping
 * address is an address associated with fraudulent transactions. The property
 * is false when the address is not associated with increased risk. The
 * property will be `null` when a shipping address is not provided.
 */
class ShippingAddress extends Address
{
    /**
     * @internal
     *
     * @var bool|null
     */
    protected $isHighRisk;

    /**
     * @internal
     *
     * @var int|null
     */
    protected $distanceToBillingAddress;

    public function __construct(?array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->isHighRisk = $this->safeArrayLookup($response['is_high_risk']);
        $this->distanceToBillingAddress
            = $this->safeArrayLookup($response['distance_to_billing_address']);
    }
}
