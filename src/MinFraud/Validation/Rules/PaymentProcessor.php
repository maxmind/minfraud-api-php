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
                'chase_paymentech',
                'cielo',
                'collector',
                'compropago',
                'conekta',
                'cuentadigital',
                'dibs',
                'digital_river',
                'elavon',
                'epayeu',
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
                'other',
                'openpaymx',
                'optimal_payments',
                'payfast',
                'paygate',
                'payone',
                'paypal',
                'paystation',
                'paytrace',
                'paytrail',
                'payture',
                'payu',
                'payulatam',
                'princeton_payment_solutions',
                'psigate',
                'qiwi',
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
                'vindicia',
                'virtual_card_services',
                'vme',
                'worldpay',
            ]
        );
    }
}
