<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Reason for the IP risk.
 *
 * This class provides both a machine-readable code and a human-readable
 * explanation of the reason for the IP risk score.
 *
 * Although more codes may be added in the future, the current codes are:
 *
 * * `ANONYMOUS_IP` - The IP address belongs to an anonymous network. See
 *   the object at `->ipAddress->traits` for more details.
 * * `BILLING_POSTAL_VELOCITY` - Many different billing postal codes have
 *   been seen on this IP address.
 * * `EMAIL_VELOCITY` - Many different email addresses have been seen on this
 *   IP address.
 * * `HIGH_RISK_DEVICE` - A high risk device was seen on this IP address.
 * * `HIGH_RISK_EMAIL` - A high risk email address was seen on this IP
 *   address in your past transactions.
 * * `ISSUER_ID_NUMBER_VELOCITY` - Many different issuer ID numbers have been
 *   seen on this IP address.
 * * `MINFRAUD_NETWORK_ACTIVITY` - Suspicious activity has been seen on this
 *   IP address across minFraud customers.
 *
 * @property-read string $code This value is a machine-readable code
 * identifying the reason.
 * @property-read string $reason This property provides a human-readable
 * explanation of the reason. The description may change at any time and
 * should not be matched against.
 */
class IpRiskReason extends AbstractModel
{
    /**
     * @internal
     *
     * @var string
     */
    protected $code;

    /**
     * @internal
     *
     * @var string
     */
    protected $reason;

    public function __construct(array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->code = $this->safeArrayLookup($response['code']);
        $this->reason = $this->safeArrayLookup($response['reason']);
    }
}
