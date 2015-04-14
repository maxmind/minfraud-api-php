<?php

namespace MaxMind\Test;

use MaxMind\MinFraud;
use MaxMind\MinFraud\Model\Insights;
use MaxMind\MinFraud\Model\Score;

class MinFraudData
{
    public static function insightsFullResponse()
    {
        return self::$fullResponse;
    }

    public static function scoreFullResponse()
    {
        return array(
            'risk_score' => self::$fullResponse['risk_score'],
            'id' => self::$fullResponse['id'],
            'credits_remaining' => self::$fullResponse['credits_remaining'],
            'warnings' => self::$fullResponse['warnings']
        );
    }

    public static $fullRequest = array(
        'event' => array(
            'transaction_id' => 'txn3134133',
            'shop_id'        => 's2123',
            'time'           => '2012-04-12T23:20:50.52Z',
            'type'           => 'purchase',
        ),
        'account' => array(
            'user_id'      => 3132,
            'username_md5' => '4f9726678c438914fa04bdb8c1a24088',
        ),
        'email' => array(
            'address' => 'test@maxmind.com',
            'domain'  => 'maxmind.com',
        ),
        'billing' => array(
            'first_name'         => 'First',
            'last_name'          => 'Last',
            'company'            => 'Company',
            'address'            => '101 Address Rd.',
            'address_2'          => 'Unit 5',
            'city'               => 'City of Thorns',
            'region'             => 'CT',
            'country'            => 'US',
            'postal'             => '06510',
            'phone_number'       => '323-123-4321',
            'phone_country_code' => '1',
        ),
        'shipping' => array(
            'first_name'         => 'ShipFirst',
            'last_name'          => 'ShipLast',
            'company'            => 'ShipCo',
            'address'            => '322 Ship Addr. Ln.',
            'address_2'          => 'St. 43',
            'city'               => 'Nowhere',
            'region'             => 'OK',
            'country'            => 'US',
            'postal'             => '73003',
            'phone_number'       => '403-321-2323',
            'phone_country_code' => '1',
            'delivery_speed'     => 'same-day',
        ),
        'payment' => array(
            'processor'             => 'stripe',
            'authorization_outcome' => 'declined',
            'decline_code'          => 'invalid number',
        ),
        'credit_card' => array(
            'issuer_id_number'        => '323132',
            'last_4_digits'           => '7643',
            'bank_name'               => 'Bank of No Hope',
            'bank_phone_country_code' => '1',
            'bank_phone_number'       => '800-342-1232',
            'avs_result'              => 'Y',
            'cvv_result'              => 'N',
        ),
        'order' => array(
            'amount'          => 323.21,
            'currency'        => 'USD',
            'discount_code'   => 'FIRST',
            'affiliate_id'    => 'af12',
            'subaffiliate_id' => 'saf42',
            'referrer_uri'    => 'http://www.amazon.com/',
        ),
        'shopping_cart' => array(
            array(
                'category' => 'pets',
                'item_id'  => 'ad23232',
                'quantity' => 2,
                'price'    => 20.43,
            ),
            array(
                'category' => 'beauty',
                'item_id'  => 'bst112',
                'quantity' => 1,
                'price'    => 100.00,
            ),
        ),
        'device' => array(
            'ip_address' => '81.2.69.160',
            'user_agent' =>
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
            'accept_language' => 'en-US,en;q=0.8',
        )
    );

    private static $fullResponse =     array(
        'id'          => '27D26476-E2BC-11E4-92B8-962E705B4AF5',
        'risk_score'  => 0.01,
        'credits_remaining' => 1000,
        'ip_location' => array(
            'city' => array(
                'confidence' => 42,
                'geoname_id' => 2643743,
                'names'      => array(
                    'de'      => 'London',
                    'en'      => 'London',
                    'es'      => 'Londres',
                    'fr'      => 'Londres',
                    'ja'      => 'ロンドン',
                    'pt-BR' => 'Londres',
                    'ru'      => 'Лондон'
                )
            ),
            'continent' => array(
                'code'       => 'EU',
                'geoname_id' => 6255148,
                'names'      => array(
                    'de'      => 'Europa',
                    'en'      => 'Europe',
                    'es'      => 'Europa',
                    'fr'      => 'Europe',
                    'ja'      => 'ヨーロッパ',
                    'pt-BR' => 'Europa',
                    'ru'      => 'Европа',
                    'zh-CN' => '欧洲'
                )
            ),
            'country' => array(
                'confidence'   => 99,
                'geoname_id'   => 2635167,
                'is_high_risk' => false,
                'iso_code'     => 'GB',
                'names'        => array(
                    'de'      => 'Vereinigtes Königreich',
                    'en'      => 'United Kingdom',
                    'es'      => 'Reino Unido',
                    'fr'      => 'Royaume-Uni',
                    'ja'      => 'イギリス',
                    'pt-BR' => 'Reino Unido',
                    'ru'      => 'Великобритания',
                    'zh-CN' => '英国'
                )
            ),
            'location' => array(
                'accuracy_radius' => 96,
                'latitude'        => 51.5142,
                'local_time'      => '2012-04-13T00:20:50+01:00',
                'longitude'       => -0.0931,
                'time_zone'       => 'Europe/London'
            ),
            'registered_country' => array(
                'geoname_id' => 6252001,
                'iso_code'   => 'US',
                'names'      => array(
                    'de'      => 'USA',
                    'en'      => 'United States',
                    'es'      => 'Estados Unidos',
                    'fr'      => 'États-Unis',
                    'ja'      => 'アメリカ合衆国',
                    'pt-BR' => 'Estados Unidos',
                    'ru'      => 'США',
                    'zh-CN' => '美国'
                )
            ),
            'subdivisions' => [
                array(
                    'confidence' => 42,
                    'geoname_id' => 6269131,
                    'iso_code'   => 'ENG',
                    'names'      => array(
                        'en'      => 'England',
                        'es'      => 'Inglaterra',
                        'fr'      => 'Angleterre',
                        'pt-BR' => 'Inglaterra'
                    )
                )
            ],
            'traits' => array(
                'domain'       => 'in-addr.arpa',
                'ip_address'   => '81.2.69.160',
                'isp'          => 'Andrews & Arnold Ltd',
                'organization' => 'STONEHOUSE office network',
                'user_type'    => 'government'
                )

        ),
        'billing_address' => array(
            'is_postal_in_city'       => false,
            'latitude'                => 41.310571,
            'longitude'               => -72.922891,
            'distance_to_ip_location' => 5465,
            'is_in_ip_country'        => false,
        ),
        'credit_card' => array(
            'issuer' => array(
                'name'                  => 'Bank of No Hope',
                'matches_provided_name' => true,
                'phone_number'          => '8003421232',
                'matches_provided_phone_number' =>
                    true,
            ),
            'country' => 'US',
            'is_issued_in_billing_address_country' =>
                true,
            'is_prepaid' => true

        ),
        'shipping_address' => array(
            'distance_to_billing_address' => 2227,
            'distance_to_ip_location'     => 7456,
            'is_in_ip_country'  => false,
            'is_high_risk'      => false,
            'is_postal_in_city' => false,
            'latitude'          => 35.704729,
            'longitude'         => -97.568619
        ),
        'warnings' => array(
            array(
                'code'  => 'INPUT_INVALID',
                'input' => array('account', 'user_id'),
                'warning' =>
                    'Encountered value at /account/user_id that does meet the required constraints',
            ),
            array(
                'code'  => 'INPUT_INVALID',
                'input' => array('account', 'username_md5'),
                'warning' =>
                    'Encountered value at /account/username_md5 that does meet the required constraints',
            ),
        )
    );
}
