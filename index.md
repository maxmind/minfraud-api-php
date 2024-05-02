---
layout: default
title: minFraud Score and Insights PHP API
language: php
version: v3.0.1
---

# MaxMind minFraud Factors, Insights, Score PHP API #

## Description ##

This package provides an API for the [MaxMind minFraud Score, Insights, and
Factors web services](https://dev.maxmind.com/minfraud/).

## Install via Composer ##

We recommend installing this package with [Composer](https://getcomposer.org/).

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
[phar archive](https://php.net/manual/en/book.phar.php) containing most of the
dependencies for this API. The latest phar archive is available on
[our releases page](https://github.com/maxmind/minfraud-api-php/releases).

### Install Dependencies ###

Please note that you must have the PHP [cURL
extension](https://php.net/manual/en/book.curl.php) installed to use this
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
Page](https://maxmind.github.io/minfraud-api-php/) under the "API" tab.

## Usage ##

This library provides access to both the [minFraud (Score, Insights and
Factors)](https://dev.maxmind.com/minfraud/)
and [Report Transaction](https://dev.maxmind.com/minfraud/report-transaction/) APIs.

### minFraud API ###

To use the minFraud API, create a new `\MaxMind\MinFraud` object. The constructor
takes your MaxMind account ID, license key, and an optional `options` array as
arguments. This object is immutable. See the API documentation for the possible options.

For instance, to use the Sandbox web service instead of the production web service, you can provide the host option:

```php
$mf = new MinFraud(1, 'ABCD567890', [ 'host' => 'sandbox.maxmind.com' ]);
```

Build up the request using the `->with*` methods as shown below. Each method call returns a new object. The previous object is not modified.

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

#### minFraud Exceptions ####

All externally visible exceptions are in the `\MaxMind\Exception` namespace.
The possible exceptions are:

* `InvalidInputException` - This will be thrown when a `->with*` method is
  called with invalid input data.
* `AuthenticationException` - This will be thrown on calling `->score()`,
  `->insights()`, or `->factors()` when the server is unable to authenticate
  the request, e.g., if the license key or account ID is invalid.
* `InsufficientFundsException` - This will be thrown on calling `->score()`,
  `->insights()`, or `->factors()` when your account is out of funds.
* `InvalidRequestException` - This will be thrown on calling `->score()`,
  `->insights()`, or `->factors()` when the server rejects the request for
  another reason such as invalid JSON in the POST.
* `HttpException` - This will be thrown on calling `->score()`, `->insights()`,
  or `->factors()` when an unexpected HTTP error occurs such as a firewall
  interfering with the request to the server.
* `WebServiceException` - This will be thrown on calling `->score()`,
  `->insights()`, or `->factors()` when some other error occurs. This also
  serves as the base class for the above exceptions.


#### minFraud Example ####

```php
<?php
require_once 'vendor/autoload.php';
use MaxMind\MinFraud;

# The constructor for MinFraud takes your account ID, your license key, and
# optionally an array of options.
$mf = new MinFraud(1, 'ABCD567890');

# Note that each ->with*() call returns a new immutable object. This means
# that if you separate the calls into separate statements without chaining,
# you should assign the return value to a variable each time.
$request = $mf->withDevice(
    ipAddress: '152.216.7.110',
    sessionAge: 3600.5,
    sessionId: 'foobar',
    userAgent: 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
    acceptLanguage: 'en-US,en;q=0.8'
)->withEvent(
    transactionId: 'txn3134133',
    shopId: 's2123',
    time: '2012-04-12T23:20:50+00:00',
    type: 'purchase'
)->withAccount(
    userId: 3132,
    usernameMd5: '4f9726678c438914fa04bdb8c1a24088'
)->withEmail(
    address: 'test@maxmind.com',
    domain: 'maxmind.com'
)->withBilling(
    firstName: 'First',
    lastName: 'Last',
    company: 'Company',
    address: '101 Address Rd.',
    address2: 'Unit 5',
    city: 'New Haven',
    region: 'CT',
    country: 'US',
    postal: '06510',
    phoneNumber: '123-456-7890',
    phoneCountryCode: '1'
)->withShipping(
    firstName: 'ShipFirst',
    lastName: 'ShipLast',
    company: 'ShipCo',
    address: '322 Ship Addr. Ln.',
    address2: 'St. 43',
    city: 'Nowhere',
    region: 'OK',
    country: 'US',
    postal: '73003',
    phoneNumber: '123-456-0000',
    phoneCountryCode: '1',
    deliverySpeed: 'same_day'
)->withPayment(
    processor: 'stripe',
    wasAuthorized: false,
    declineCode: 'invalid number'
)->withCreditCard(
    issuerIdNumber: '411111',
    lastDigits: '7643',
    bankName: 'Bank of No Hope',
    bankPhoneCountryCode: '1',
    bankPhoneNumber: '123-456-1234',
    avsResult: 'Y',
    cvvResult: 'N',
    was3dSecureSuccessful: true
)->withOrder(
    amount: 323.21,
    currency: 'USD',
    discountCode: 'FIRST',
    isGift: true,
    hasGiftMessage: false,
    affiliateId: 'af12',
    subaffiliateId: 'saf42',
    referrerUri: 'http://www.amazon.com/'
)->withShoppingCartItem(
    category: 'pets',
    itemId: 'leash-0231',
    quantity: 2,
    price: 20.43
)->withShoppingCartItem(
    category: 'beauty',
    itemId: 'msc-1232',
    quantity: 1,
    price: 100.00
)->withCustomInputs([
   'section'            => 'news',
   'previous_purchases' => 19,
   'discount'           => 3.2,
   'previous_user'      => true,
]);

# To get the minFraud Factors response model, use ->factors():
$factorsResponse = $request->factors();

print($factorsResponse->subscores->emailAddress . "\n");

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

### Report Transactions API ###

MaxMind encourages the use of this API as data received through this channel is
used to continually improve the accuracy of our fraud detection algorithms.

To use the Report Transactions API, create a new
`\MaxMind\MinFraud\ReportTransaction` object. The constructor takes your MaxMind
account ID, license key, and an optional options array as arguments. This object
is immutable. You then send one or more reports using the `->report` method as
shown below.

If there is a validation error in the data passed to the `->report` method, a
`\MaxMind\Exception` will be thrown. This validation can be disabled by
setting `validateInput` to `false` in the options array for
`\MaxMind\MinFraud\ReportTransaction`, but it is recommended that you keep it on
at least through development as it will help ensure that you are sending valid
data to the web service.

If the report is successful, nothing is returned. If the report fails, an
exception with be thrown.

See the API documentation for more details.

#### Report Transaction Exceptions ####

All externally visible exceptions are in the `\MaxMind\Exception` namespace.
The possible exceptions are:

* `InvalidInputException` - This will be thrown when the `->report()` method is
  called with invalid input data or when the required `ip_address` or `tag`
  fields are missing.
* `AuthenticationException` - This will be thrown on calling `->report()`,
  when the server is unable to authenticate the request, e.g., if the license
  key or account ID is invalid.
* `InvalidRequestException` - This will be thrown on calling `->report()` when
  the server rejects the request for another reason such as invalid JSON in the
  POST.
* `HttpException` - This will be thrown on calling `->report()` when an
  unexpected HTTP error occurs such as a firewall interfering with the request
  to the server.
* `WebServiceException` - This will be thrown on calling `->report()` when some
  other error occurs. This also serves as the base class for the above
  exceptions.

#### Report Transaction Example ####

```php
<?php
require_once 'vendor/autoload.php';
use MaxMind\MinFraud\ReportTransaction;

# The constructor for ReportTransaction takes your account ID, your license key,
# and optionally an array of options.
$rt = new ReportTransaction(1, 'ABCD567890');

$rt->report(
    ipAddress: '152.216.7.110',
    tag: 'chargeback',
    chargebackCode: 'UA02',
    minfraudId: '26ae87e4-5112-4f76-b0f7-4132d45d72b2',
    maxmindId: 'aBcDeFgH',
    notes: 'Found due to non-existent shipping address',
    transactionId: 'cart123456789'
);
```

## Support ##

Please report all issues with this code using the
[GitHub issue tracker](https://github.com/maxmind/minfraud-api-php/issues).

If you are having an issue with the minFraud service that is not specific
to the client API, please see
[our support page](https://www.maxmind.com/en/support).

## Requirements  ##

This code requires PHP 8.1 or greater. Older versions of PHP are not
supported.

There are several other dependencies as defined in the `composer.json` file.

## Contributing ##

Patches and pull requests are encouraged. All code should follow the PSR-2
style guidelines. Please include unit tests whenever possible.

## Versioning ##

This API uses [Semantic Versioning](https://semver.org/).

## Copyright and License ##

This software is Copyright (c) 2015-2024 by MaxMind, Inc.

This is free software, licensed under the Apache License, Version 2.0.
