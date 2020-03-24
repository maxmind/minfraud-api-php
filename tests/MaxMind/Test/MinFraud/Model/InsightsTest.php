<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Insights;
use MaxMind\Test\MinFraudData as Data;

/**
 * @coversNothing
 */
class InsightsTest extends ScoreTest
{
    protected function response()
    {
        return Data::insightsFullResponse();
    }

    protected function model()
    {
        return new Insights($this->response(), ['fr']);
    }

    public function testInsightsProperties()
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
                  'isSatelliteProvider',
                  'isTorExitNode',
                 ] as $property) {
            $this->assertTrue($insights->ipAddress->traits->$property);
        }
    }
}
