<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Factors;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 *
 * @internal
 */
class FactorsTest extends InsightsTest
{
    protected function response(): array
    {
        return Data::factorsFullResponse();
    }

    protected function model(): Factors
    {
        return new Factors($this->response(), ['fr']);
    }

    public function testFactorsProperties(): void
    {
        $array = $this->response();
        $factors = $this->model();

        foreach ([
            'avs_result' => 'avsResult',
            'billing_address' => 'billingAddress',
            'billing_address_distance_to_ip_location' => 'billingAddressDistanceToIpLocation',
            'browser' => 'browser',
            'chargeback' => 'chargeback',
            'country' => 'country',
            'country_mismatch' => 'countryMismatch',
            'cvv_result' => 'cvvResult',
            'device' => 'device',
            'email_address' => 'emailAddress',
            'email_domain' => 'emailDomain',
            'email_local_part' => 'emailLocalPart',
            'issuer_id_number' => 'issuerIdNumber',
            'order_amount' => 'orderAmount',
            'phone_number' => 'phoneNumber',
            'shipping_address' => 'shippingAddress',
            'shipping_address_distance_to_ip_location' => 'shippingAddressDistanceToIpLocation',
            'time_of_day' => 'timeOfDay',
        ] as $key => $method) {
            $this->assertSame(
                $array['subscores'][$key],
                $factors->subscores->{$method},
                $key
            );
        }
    }
}
