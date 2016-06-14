<?php

namespace MaxMind\MinFraud\Validation\Rules;

use Respect\Validation\Rules\AbstractWrapper;
use Respect\Validation\Validator as v;

/**
 * @internal
 */
class PaymentProcessor extends AbstractWrapper
{
    public function __construct()
    {
        $this->validatable = v::in(
            [
                'adyen',
                'altapay',
                'amazon_payments',
                'authorizenet',
                'balanced',
                'beanstream',
                'bluepay',
                'braintree',
                'ccnow',
                'chase_paymentech',
                'cielo',
                'collector',
                'compropago',
                'concept_payments',
                'conekta',
                'cuentadigital',
                'dalpay',
                'dibs',
                'digital_river',
                'ecomm365',
                'elavon',
                'epay',
                'eprocessing_network',
                'eway',
                'first_data',
                'global_payments',
                'ingenico',
                'internetsecure',
                'intuit_quickbooks_payments',
                'iugu',
                'mastercard_payment_gateway',
                'mercadopago',
                'merchant_esolutions',
                'mirjeh',
                'mollie',
                'moneris_solutions',
                'nmi',
                'openpaymx',
                'optimal_payments',
                'orangepay',
                'other',
                'pacnet_services',
                'payfast',
                'paygate',
                'payone',
                'paypal',
                'payplus',
                'paystation',
                'paytrace',
                'paytrail',
                'payture',
                'payu',
                'payulatam',
                'pinpayments',
                'princeton_payment_solutions',
                'psigate',
                'qiwi',
                'quickpay',
                'raberil',
                'rede',
                'redpagos',
                'rewardspay',
                'sagepay',
                'simplify_commerce',
                'skrill',
                'smartcoin',
                'sps_decidir',
                'stripe',
                'telerecargas',
                'towah',
                'usa_epay',
                'verepay',
                'vindicia',
                'virtual_card_services',
                'vme',
                'worldpay',
            ]
        );
    }
}
