<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model of the Insights response.
 */
class Insights implements \JsonSerializable
{
    /**
     * @var BillingAddress an object containing minFraud data related to the
     *                     billing address used in the transaction
     */
    public readonly BillingAddress $billingAddress;

    /**
     * @var Phone an object containing minFraud data related to the billing
     *            phone used in the transaction
     */
    public readonly Phone $billingPhone;

    /**
     * @var CreditCard an object containing minFraud data about the credit
     *                 card used in the transaction
     */
    public readonly CreditCard $creditCard;

    /**
     * @var Device this object contains information about the device that
     *             MaxMind believes is associated with the IP address passed
     *             in the request
     */
    public readonly Device $device;

    /**
     * @var Disposition an object containing the disposition set by custom
     *                  rules
     */
    public readonly Disposition $disposition;

    /**
     * @var Email this object contains information about the email address
     *            passed in the request
     */
    public readonly Email $email;

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
     * @var IpAddress an object containing GeoIP2 and minFraud Insights
     *                information about the geolocated IP address
     */
    public readonly IpAddress $ipAddress;

    /**
     * @var int the approximate number of queries remaining for this service
     *          before your account runs out of funds
     */
    public readonly int $queriesRemaining;

    /**
     * @var float This property contains the risk score, from 0.01 to 99. A
     *            higher score indicates a higher risk of fraud. For example, a
     *            score of 20 indicates a 20% chance that a transaction is
     *            fraudulent. We never return a risk score of 0, since all
     *            transactions have the possibility of being fraudulent.
     *            Likewise we never return a risk score of 100.
     */
    public readonly float $riskScore;

    /**
     * @var ShippingAddress an object containing minFraud data related to the
     *                      shipping address used in the transaction
     */
    public readonly ShippingAddress $shippingAddress;

    /**
     * @var Phone an object containing minFraud data related to the shipping
     *            phone used in the transaction
     */
    public readonly Phone $shippingPhone;

    /**
     * @var array This array contains \MaxMind\MinFraud\Model\Warning objects
     *            detailing issues with the request that was sent, such as
     *            invalid or unknown inputs. It is highly recommended that
     *            you check this array for issues when integrating the web
     *            service.
     */
    public readonly array $warnings;

    public function __construct(array $response, array $locales = ['en'])
    {
        $this->disposition
            = new Disposition($response['disposition'] ?? []);
        $this->fundsRemaining = $response['funds_remaining'];
        $this->queriesRemaining = $response['queries_remaining'];
        $this->id = $response['id'];
        $this->riskScore = $response['risk_score'];

        $warnings = [];
        if (isset($response['warnings'])) {
            foreach ($response['warnings'] as $warning) {
                $warnings[] = new Warning($warning);
            }
        }
        $this->warnings = $warnings;

        $this->billingAddress = new BillingAddress($response['billing_address'] ?? []);
        $this->billingPhone = new Phone($response['billing_phone'] ?? []);
        $this->creditCard = new CreditCard($response['credit_card'] ?? []);
        $this->device = new Device($response['device'] ?? []);
        $this->email = new Email($response['email'] ?? []);
        $this->ipAddress = new IpAddress($response['ip_address'] ?? [], $locales);
        $this->shippingAddress = new ShippingAddress($response['shipping_address'] ?? []);
        $this->shippingPhone = new Phone($response['shipping_phone'] ?? []);
    }

    public function jsonSerialize(): array
    {
        $js = [];

        $billingAddress = $this->billingAddress->jsonSerialize();
        if (!empty($billingAddress)) {
            $js['billing_address'] = $billingAddress;
        }

        $billingPhone = $this->billingPhone->jsonSerialize();
        if (!empty($billingPhone)) {
            $js['billing_phone'] = $billingPhone;
        }

        $creditCard = $this->creditCard->jsonSerialize();
        if (!empty($creditCard)) {
            $js['credit_card'] = $creditCard;
        }

        $device =
          $this->device->jsonSerialize();
        if (!empty($device)) {
            $js['device'] = $device;
        }

        $disposition = $this->disposition->jsonSerialize();
        if (!empty($disposition)) {
            $js['disposition'] = $disposition;
        }

        $email = $this->email->jsonSerialize();
        if (!empty($email)) {
            $js['email'] = $email;
        }

        $js['funds_remaining'] = $this->fundsRemaining;

        $ipAddress = $this->ipAddress->jsonSerialize();
        if (!empty($ipAddress)) {
            $js['ip_address'] = $ipAddress;
        }

        $js['id'] = $this->id;

        $js['queries_remaining'] = $this->queriesRemaining;

        $js['risk_score'] = $this->riskScore;

        $shippingAddress = $this->shippingAddress->jsonSerialize();
        if (!empty($shippingAddress)) {
            $js['shipping_address'] = $shippingAddress;
        }

        $shippingPhone = $this->shippingPhone->jsonSerialize();
        if (!empty($shippingPhone)) {
            $js['shipping_phone'] = $shippingPhone;
        }

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
