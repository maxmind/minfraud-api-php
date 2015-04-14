# MaxMind minFraud v2.0 PHP API

This is an early pre-release version. Don't use it.


## Example

```php
$mf = new MinFraud(6, '1234567890');

$response = $mf->withEvent(
    array(
        'transaction_id' => 'txn3134133',
        'shop_id'        => 's2123',
        'time'           => '2012-04-12T23:20:50.52Z',
        'type'           => 'purchase',
    )
)->withAccount(
    array(
        'user_id'      => 3132,
        'username_md5' => '4f9726678c438914fa04bdb8c1a24088',
    )
)->withEmail(
    array(
        'address' => 'test@maxmind.com',
        'domain'  => 'maxmind.com',
    )
)->withBilling(
    array(
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
    )
)->withShipping(
    array(
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
    )
)->withPayment(
    array(
        'processor'             => 'stripe',
        'authorization_outcome' => 'declined',
        'decline_code'          => 'invalid number',
    )
)->withCreditCard(
    array(
        'issuer_id_number'        => '323132',
        'last_4_digits'           => '7643',
        'bank_name'               => 'Bank of No Hope',
        'bank_phone_country_code' => '1',
        'bank_phone_number'       => '800-342-1232',
        'avs_result'              => 'Y',
        'cvv_result'              => 'N',
    )
)->withOrder(
    array(
        'amount'          => 323.21,
        'currency'        => 'USD',
        'discount_code'   => 'FIRST',
        'affiliate_id'    => 'af12',
        'subaffiliate_id' => 'saf42',
        'referrer_uri'    => 'http://www.amazon.com/',
    )
)->withShoppingCartItem(
    array(
        'category' => 'pets',
        'item_id'  => 'ad23232',
        'quantity' => 2,
        'price'    => 20.43,
    )
)->withShoppingCartItem(
    array(
        'category' => 'beauty',
        'item_id'  => 'bst112',
        'quantity' => 1,
        'price'    => 100.00,
    )
)->withDevice(
    array(
        'ip_address' => '81.2.69.160',
        'user_agent' =>
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
        'accept_language' => 'en-US,en;q=0.8',
    )
)->insights();
```
