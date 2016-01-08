<?php

namespace MaxMind\Test\MinFraud\Model;

use MaxMind\MinFraud\Model\Insights;
use MaxMind\Test\MinFraudData as Data;

class InsightsTest extends \PHPUnit_Framework_TestCase
{
    public function testInsights()
    {
        $array = Data::insightsFullResponse();

        $insights = new Insights($array, ['fr']);

        $this->assertEquals(
            $array['id'],
            $insights->id,
            'id'
        );

        $this->assertEquals(
            $array['credits_remaining'],
            $insights->creditsRemaining,
            'credits remaining'
        );

        $this->assertEquals(
            $array['risk_score'],
            $insights->riskScore,
            'riskScore'
        );

        $this->assertEquals(
            count($array['warnings']),
            count($insights->warnings),
            'correct number of warnings'
        );

        $this->assertEquals(
            $array['warnings'][0]['code'],
            $insights->warnings[0]->code,
            'first warning has correct code'
        );

        // We test one field in each contained object to ensure that data
        // is being passed correctly to these objects. There are specific
        // tests of the objects themselves in the appropriately named classes.
        $this->assertEquals(
            $array['ip_address']['country']['names']['fr'],
            $insights->ipAddress->country->name,
            'correct French country name'
        );

        $this->assertEquals(
            $array['credit_card']['issuer']['name'],
            $insights->creditCard->issuer->name,
            'correct CC issuer name'
        );

        $this->assertEquals(
            $array['shipping_address']['is_high_risk'],
            $insights->shippingAddress->isHighRisk,
            'correct shipping address risk'
        );

        $this->assertEquals(
            $array['billing_address']['latitude'],
            $insights->billingAddress->latitude,
            'correct billing latitude'
        );
    }
}
