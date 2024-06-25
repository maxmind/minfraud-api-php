<?php

declare(strict_types=1);

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Insights;
use MaxMind\MinFraud\Model\Score;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 *
 * @internal
 */
class InsightsTest extends ScoreTest
{
    protected function response(): array
    {
        return Data::insightsFullResponse();
    }

    /**
     * This is specified here as PHP 7.3 doesn't allow us to set it to
     * Insights.
     */
    protected function model(): Insights|Score
    {
        return new Insights($this->response(), ['fr']);
    }

    public function testInsightsProperties(): void
    {
        $array = $this->response();
        $insights = $this->model();

        // We test one field in each contained object to ensure that data
        // is being passed correctly to these objects. There are specific
        // tests of the objects themselves in the appropriately named classes.
        $this->assertSame(
            $array['ip_address']['country']['names']['fr'],
            $insights->ipAddress->country->name,
            'correct French country name'
        );

        $this->assertSame(
            $array['ip_address']['risk_reasons'][0]['code'],
            $insights->ipAddress->riskReasons[0]->code,
            'correct IP risk reason code'
        );

        $this->assertTrue(
            $insights->ipAddress->country->isInEuropeanUnion,
            'country is in EU'
        );

        $this->assertFalse(
            $insights->ipAddress->registeredCountry->isInEuropeanUnion,
            'registered country is in EU'
        );

        $this->assertFalse(
            $insights->ipAddress->representedCountry->isInEuropeanUnion,
            'represented country is in EU'
        );

        $this->assertSame(
            $array['credit_card']['issuer']['name'],
            $insights->creditCard->issuer->name,
            'correct CC issuer name'
        );

        $this->assertSame(
            $array['shipping_address']['is_high_risk'],
            $insights->shippingAddress->isHighRisk,
            'correct shipping address risk'
        );

        $this->assertSame(
            $array['billing_address']['latitude'],
            $insights->billingAddress->latitude,
            'correct billing latitude'
        );

        $this->assertTrue(
            isset($insights->billingAddress->latitude),
            'isset works for billing latitude'
        );

        $this->assertSame(
            $array['email']['domain']['first_seen'],
            $insights->email->domain->firstSeen,
            'correct email domain first seen'
        );

        $this->assertFalse(
            isset($insights->unknown),
            'isset returns false for unknown method'
        );

        $this->assertNotEmpty(
            $insights->billingAddress->latitude,
            'empty works for billing latitude'
        );

        foreach (['isAnonymous',
            'isAnonymousVpn',
            'isHostingProvider',
            'isPublicProxy',
            'isTorExitNode',
        ] as $property) {
            $this->assertTrue($insights->ipAddress->traits->{$property});
        }

        $this->assertSame(
            $array['ip_address']['traits']['mobile_country_code'],
            $insights->ipAddress->traits->mobileCountryCode,
            'correct mobile country code'
        );

        $this->assertSame(
            $array['ip_address']['traits']['mobile_network_code'],
            $insights->ipAddress->traits->mobileNetworkCode,
            'correct mobile network code'
        );

        $this->assertSame(
            $array['billing_phone']['country'],
            $insights->billingPhone->country,
            'correct billing phone country'
        );

        $this->assertSame(
            $array['shipping_phone']['country'],
            $insights->shippingPhone->country,
            'correct shipping phone country'
        );
    }
}
