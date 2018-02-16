# MaxMind minFraud Factors, Insights, Score PHP API #

## Description ##

This package provides an API for the [MaxMind minFraud Score, Insights, and
Factors web services](https://dev.maxmind.com/minfraud/).

## Install via Composer ##

We recommend installing this package with [Composer](http://getcomposer.org/).

### Download Composer ###

To download Composer, run in the root directory of your project:

```bash
curl -sS https://getcomposer.org/installer | php
```

You should now have the file `composer.phar` in your project directory.

### Install Dependencies ###

Run in your project root:

```
php composer.phar require maxmind/minfraud:~1.0
```

You should now have the files `composer.json` and `composer.lock` as well as
the directory `vendor` in your project directory. If you use a version control
system, `composer.json` should be added to it.

### Require Autoloader ###

After installing the dependencies, you need to require the Composer autoloader
from your code:

```php
require 'vendor/autoload.php';
```

## Install via Phar ##

Although we strongly recommend using Composer, we also provide a
[phar archive](http://php.net/manual/en/book.phar.php) containing most of the
dependencies for this API. The latest phar archive is available on
[our releases page](https://github.com/maxmind/minfraud-api-php/releases).

### Install Dependencies ###

Please note that you must have the PHP [cURL
extension](http://php.net/manual/en/book.curl.php) installed to use this
archive. For Debian based distributions, this can typically be found in the
the `php-curl` package. For other operating systems, please consult the
relevant documentation. After installing the extension you may need to
restart your web server.

If you are missing this extension, you will see errors like the following:

```
PHP Fatal error:  Uncaught Error: Call to undefined function MaxMind\WebService\curl_version()
```

### Require Package ###

To use the archive, just require it from your script:

```php
require 'minfraud.phar';
```

## API Documentation ###

More detailed API documentation is available on [our GitHub
Page](http://maxmind.github.io/minfraud-api-php/) under the "API" tab.

## Usage ##

To use this API, create a new `\MaxMind\MinFraud` object. The constructor
takes your MaxMind account ID, license key, and an optional options array as
arguments. This object is immutable. You then build up the request using the
`->with*` methods as shown below. Each method call returns a new object. The
previous object is not modified.

If there is a validation error in the data passed to a `->with*` method, a
`\MaxMind\Exception` will be thrown. This validation can be disabled by
setting `validateInput` to `false` in the options array for
`\MaxMind\MinFraud`, but it is recommended that you keep it on at least
through development as it will help ensure that you are sending valid data to
the web service.

After creating the request object, send a Score request by calling
`->score()`, an Insights request by calling `->insights()`, or a Factors
request by calling `->factors()`. If the request succeeds, a model object will
be returned for the endpoint. If the request fails, an exception will be
thrown.

See the API documentation for more details.

### Exceptions ###

All externally visible exceptions are in the `\MaxMind\Exception` namespace.
The possible exceptions are:

* `InvalidInputException` - This will be thrown when a `->with*` method is
  called with invalid input data or when `->score()` or `->insights()` is
  called on a request where the required `ip_address` field in the `device`
  array is missing.
* `AuthenticationException` - This will be thrown on calling `->score()` or
  `->insights()` when the server is unable to authenticate the request, e.g.,
  if the license key or account ID is invalid.
* `InsufficientFundsException` - This will be thrown on calling `->score()` or
  `->insights()` when your account is out of funds.
* `InvalidRequestException` - This will be thrown on calling `->score()` or
  `->insights()` when the server rejects the request for another reason such
  as invalid JSON in the POST.
* `HttpException` - This will be thrown on calling `->score()` or
  `->insights()` when an unexpected HTTP error occurs such as a firewall
  interfering with the request to the server.
* `WebServiceException` - This will be thrown on calling `->score()` or
  `->insights()` when some other error occurs. This also serves as the base
  class for the above exceptions.


## Example

```php
<?php
require_once 'vendor/autoload.php';
use MaxMind\MinFraud;

# The constructor for MinFraud takes your account ID, your license key, and
# optionally an array of options.
$mf = new MinFraud(1, 'ABCD567890');

$request = $mf->withDevice([
    'ip_address'  => '81.2.69.160',
    'session_age' => 3600.5,
    'session_id'  => 'foobar',
    'user_agent'  =>
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
    'accept_language' => 'en-US,en;q=0.8',
])->withEvent([
    'transaction_id' => 'txn3134133',
    'shop_id'        => 's2123',
    'time'           => '2012-04-12T23:20:50+00:00',
    'type'           => 'purchase',
])->withAccount([
    'user_id'      => 3132,
    'username_md5' => '4f9726678c438914fa04bdb8c1a24088',
])->withEmail([
    'address' => 'test@maxmind.com',
    'domain'  => 'maxmind.com',
])->withBilling([
    'first_name'         => 'First',
    'last_name'          => 'Last',
    'company'            => 'Company',
    'address'            => '101 Address Rd.',
    'address_2'          => 'Unit 5',
    'city'               => 'New Haven',
    'region'             => 'CT',
    'country'            => 'US',
    'postal'             => '06510',
    'phone_number'       => '323-123-4321',
    'phone_country_code' => '1',
])->withShipping([
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
    'delivery_speed'     => 'same_day',
])->withPayment([
    'processor'             => 'stripe',
    'was_authorized'        => false,
    'decline_code'          => 'invalid number',
])->withCreditCard([
    'issuer_id_number'        => '323132',
    'last_4_digits'           => '7643',
    'bank_name'               => 'Bank of No Hope',
    'bank_phone_country_code' => '1',
    'bank_phone_number'       => '800-342-1232',
    'avs_result'              => 'Y',
    'cvv_result'              => 'N',
])->withOrder([
    'amount'           => 323.21,
    'currency'         => 'USD',
    'discount_code'    => 'FIRST',
    'is_gift'          => true,
    'has_gift_message' => false,
    'affiliate_id'     => 'af12',
    'subaffiliate_id'  => 'saf42',
    'referrer_uri'     => 'http://www.amazon.com/',
])->withShoppingCartItem([
    'category' => 'pets',
    'item_id'  => 'leash-0231',
    'quantity' => 2,
    'price'    => 20.43,
])->withShoppingCartItem([
    'category' => 'beauty',
    'item_id'  => 'msc-1232',
    'quantity' => 1,
    'price'    => 100.00,
])->withCustomInputs([
    'section'                      => 'news',
    'number_of_previous_purchases' => 19,
    'discount'                     => 3.2,
    'previous_user'                => true,
]);

# To get the minFraud Factors response model, use ->factors():
$factorsResponse = $request->factors();

print($insightsResponse->subscores->email . "\n");

# To get the minFraud Insights response model, use ->insights():
$insightsResponse = $request->insights();

print($insightsResponse->riskScore . "\n");
print($insightsResponse->creditCard->issuer->name . "\n");

foreach ($insightsResponse->warnings as $warning) {
    print($warning->warning . "\n");
}

# To get the minFraud Score response model, use ->score():
$scoreResponse = $request->score();

print($scoreResponse->riskScore . "\n");

foreach ($scoreResponse->warnings as $warning) {
    print($warning->warning . "\n");
}
```

## Support ##

Please report all issues with this code using the
[GitHub issue tracker](https://github.com/maxmind/minfraud-api-php/issues).

If you are having an issue with the minFraud service that is not specific
to the client API, please see
[our support page](http://www.maxmind.com/en/support).

## Requirements  ##

This code requires PHP 5.4 or greater. Older versions of PHP are not
supported. This library works and is tested with HHVM.

There are several other dependencies as defined in the `composer.json` file.

## Contributing ##

Patches and pull requests are encouraged. All code should follow the PSR-2
style guidelines. Please include unit tests whenever possible.

## Versioning ##

This API uses [Semantic Versioning](http://semver.org/).

## Copyright and License ##

This software is Copyright (c) 2015-2018 by MaxMind, Inc.

This is free software, licensed under the Apache License, Version 2.0.
