CHANGELOG
=========

1.22.0 (2022-08-05)
-------------------

* The model class names are no longer constructed by concatenating strings.
  This change was made to improve support for tools like PHP-Scoper.
* Added `shopify_payments` to the payment processor validation.
* Box 4.0.1 is now used to generate the `geoip2.phar` file.

1.21.0 (2022-03-28)
-------------------

* Added the input `/credit_card/country`. This is the country where the issuer
  of the card is located. This may be passed instead of
  `/credit_card/issuer_id_number` if you do not wish to pass partial account
  numbers or if your payment processor does not provide them.
* Fixed PHP 8.1 deprecation warning in internal validation classes.

1.20.0 (2022-01-25)
-------------------

* Upgraded `geoip2/geoip2` to 2.12.0. This adds mobile country code (MCC)
  and mobile network code (MNC) to minFraud Insights and Factors responses.
  These are available at `$response->ipAddress->traits->mobileCountryCode` and
  `$response->ipAddress->traits->mobileNetworkCode`. We expect this data to be
  available by late January, 2022.
* `minfraud.phar` is now generated with Box 3.x.
* Added the following new values to the payment processor validation:
  * `boacompra`
  * `boku`
  * `coregateway`
  * `fiserv`
  * `neopay`
  * `neosurf`
  * `openbucks`
  * `paysera`
  * `payvision`
  * `trustly`
  * `windcave`
* The `/credit_card/last_4_digits` input has been deprecated in favor of
  `/credit_card/last_digits` and will be removed in a future release.
  `last_digits`/`last_4_digits` also now supports two digit values in
  addition to the previous four digit values.
* Eight digit `/credit_card/issuer_id_number` inputs are now supported in
  addition to the previously accepted six digit `issuer_id_number`. In most
  cases, you should send the last four digits for `last_digits`. If you send
  an `issuer_id_number` that contains an eight digit IIN, and if the credit
  card brand is not one of the following, you should send the last two digits
  for `last_digits`:
  * `Discover`
  * `JCB`
  * `Mastercard`
  * `UnionPay`
  * `Visa`

1.19.0 (2021-08-25)
-------------------

* Added `datacap` to the payment processor validation.
* Added `ruleLabel` to minFraud output `Disposition`.
* Added `was_3d_secure_successful` to `/credit_card` validation.

1.18.0 (2021-06-07)
-------------------

* Added the following new values to the payment processor validation:
  * `cardknox`
  * `creditguard`
  * `credorax`
  * `dlocal`
  * `onpay`
  * `safecharge`

1.17.0 (2021-02-02)
-------------------

* IMPORTANT: PHP 7.3 or greater is now required.
* The dependency `Respect\Validation` has been upgraded from 1.x to 2.1.
* The `with()` method on `MaxMind\MinFraud` may now be used when
  `device` and `shopping_cart` are not set.
* Added the following new values to the payment processor validation:
  * `apple_pay`
  * `aps_payments`
* You may now enable client-side email hashing by setting `hashEmail` to
  `true` in the `MaxMind\MinFraud` constructor's options parameter. When set,
  this normalizes the email address and sends an MD5 hash of it to the web
  service rather than the plain-text address. Note that the email domain will
  still be sent in plain text.
* Added support for the IP address risk reasons in the minFraud Insights and
  Factors responses. This is available at `->ipAddress->riskReasons`. It is
  an array of `MaxMind\MinFraud\Model\IpRiskReason` objects.

1.16.1 (2020-11-02)
-------------------

* `maxmind/web-service-common` has been updated to 0.8.1 to fix an issue when
  using the `reportTransaction` method. Reported by Dmitry Malashko. GitHub
  #99.

1.16.0 (2020-10-13)
-------------------

* Added `tsys` to the payment processor validation.
* The device IP address is no longer a required input.

1.15.0 (2020-10-01)
-------------------

* IMPORTANT: PHP 7.2 or greater is now required.
* Additional type hints have been added.
* Added the `isResidentialProxy` property to `GeoIp2\Record\Traits`.

1.14.0 (2020-08-05)
-------------------

* Added the following new values to the payment processor validation:
  * `cashfree`
  * `first_atlantic_commerce`
  * `komoju`
  * `paytm`
  * `razorpay`
  * `systempay`
* Added support for the `/subscores/device`, `/subscores/email_local_part` and
  `/subscores/shipping_address` outputs. They are exposed as the `device`,
  `emailLocalPart` and `shippingAddress` properties on
  `MaxMind\MinFraud\Model\Subscores`.

1.13.0 (2020-05-29)
-------------------

* Added support for the Report Transactions API. We encourage the use of this
  API as we use data received through this channel to continually improve the
  accuracy of our fraud detection algorithms.

1.12.0 (2020-04-06)
-------------------

* Added support for the new credit card output `/credit_card/is_business`.
  This indicates whether the card is a business card. It may be accessed via
  `$response->creditCard->isBusiness` on the minFraud Insights and Factors
  response objects.

1.11.0 (2020-03-26)
-------------------

* Added support for the new email domain output `/email/domain/first_seen`.
  This may be accessed via `$response->email->domain->firstSeen` on the
  minFraud Insights and Factors response objects.
* The validation of `/event/time` now allows sub-second RFC 3339 timestamps
  in the request.
* Added the following new values to the payment processor validation:
  * `cardpay`
  * `epx`

1.10.0 (2020-02-21)
-------------------

* Added support for the `/email/is_disposable` output. This is available as
  the `isDisposable` property on `MaxMind\MinFraud\Model\Email`.
* Updated the validation on `/order/amount` and `/shopping_cart/*/price` to
  allow 0. This was an inconsistency between this library and the web
  service. Reported by Sn0wCrack. GitHub #78.

1.9.0 (2019-12-12)
------------------

* PHP 5.6 is now required.
* The client-side validation for numeric custom inputs has been updated to
  match the server-side validation. The valid range is -9,999,999,999,999
  to 9,999,999,999,999. Previously, larger numbers were allowed.
* Added the following new values to the payment processor validation:
  * `affirm`
  * `afterpay`
  * `cetelem`
  * `dotpay`
  * `ecommpay`
  * `g2a_pay`
  * `interac`
  * `klarna`
  * `mercanet`
  * `paysafecard`
* Deprecated `emailTenure` and `ipTenure` properties in
  `MaxMind\MinFraud\Model\Subscores`.
* Deprecated `isHighRisk` property in `MaxMind\MinFraud\Model\GeoIp2Country`.

1.8.0 (2019-03-07)
------------------

* Added the following new values to the payment processor validation:
  * `datacash`
  * `gocardless`
  * `payeezy`
  * `paylike`
  * `payment_express`
  * `smartdebit`
  * `synapsefi`
* Be more explicit in the documentation about the fact that we create and
  return new MinFraud objects in each `->with*()` call.

1.7.0 (2018-04-10)
------------------

* Renamed MaxMind user ID to account ID in the code and added support for the
  new `ACCOUNT_ID_REQUIRED` error code.
* Added the following new values to the payment processor validation:
  * `ccavenue`
  * `ct_payments`
  * `dalenys`
  * `oney`
  * `posconnect`
* Added support for the `/device/local_time` output. This is exposed as
  the `localTime` property on `MaxMind\MinFraud\Model\Device`.
* Added support for the `/credit_card/is_virtual` output. This is exposed as
  the `isVirtual` property on `MaxMind\MinFraud\Model\CreditCard`.
* Added `payout_change` to the `/event/type` input validation.


1.6.0 (2018-01-19)
------------------

* Upgraded `geoip2/geoip2` dependency. This version adds the
  `isInEuropeanUnion` property to `MaxMind\MinFraud\Model\GeoIp2Country`
  and `GeoIp2\Record\RepresentedCountry`. This property is `true` if the
  country is a member state of the European Union.
* Added the following new values to the payment processor validation:
  * `cybersource`
  * `transact_pro`
  * `wirecard`

1.5.0 (2017-10-30)
------------------

* TLD validation is no longer performed when validating `/email/domain` in
  order to better accommodate new gTLDs that the validation library does
  not yet know about.
* Added the following new values to the payment processor validation:
  * `bpoint`
  * `checkout_com`
  * `emerchantpay`
  * `heartland`
  * `payway`
* Updated `geoip2/geoip2` dependency to add support for GeoIP2 Precision
  Insights anonymizer fields.

1.4.0 (2017-07-10)
------------------

* Added support for custom inputs. You may set up custom inputs from your
  account portal.
* Updated the docs for `MaxMind\MinFraud\Model\Address` now that
  `isPostalInCity` may be returned for addresses world-wide.
* The `firstSeen` was added to the `Email` response model. `session_age`
  and `session_id` inputs were added to `device` input validation.
* Added the following new values to the payment processor validation:
  * `american_express_payment_gateway`
  * `bluesnap`
  * `commdoo`
  * `curopayments`
  * `ebs`
  * `exact`
  * `hipay`
  * `lemon_way`
  * `oceanpayment`
  * `paymentwall`
  * `payza`
  * `securetrading`
  * `solidtrust_pay`
  * `vantiv`
  * `vericheck`
  * `vpos`

1.3.0 (2016-11-22)
------------------

* The disposition was added to the minFraud response models. This is used to
  return the disposition of the transaction as set by the custom rules for the
  account.

1.2.0 (2016-11-11)
------------------

* Allow `/credit_card/token` input.

1.1.1 (2016-10-17)
------------------

* Correctly set the IP address risk for the Score model. Previously, it
  always returned `null`.

1.1.0 (2016-10-11)
------------------

* Added the follow new values to the event type validation: `email_change` and
  `password_reset`.
* `isset()` on model attributes now returns the correct value.

1.0.0 (2016-09-15)
------------------

* First production release. No code changes.

0.6.2 (2016-08-17)
------------------

* Added the following new values to the payment processor validation:
  `concept_payments`, `ecomm365`, `orangepay`, and `pacnet_services`.
* Upgraded `maxmind/web-service-common` to 0.3.0. This version uses
  `composer/ca-bundle` rather than our own CA bundle.

0.6.1 (2016-06-10)
------------------

* Upgraded to `maxmind/web-service-common` that supports setting a HTTP proxy.

0.6.0 (2016-06-08)
------------------

* BREAKING CHANGE: `creditsRemaining` has been removed from the web service
  models and has been replaced by `queriesRemaining`.
* Added `queriesRemaining` and `fundsRemaining`. Note that `fundsRemaining`
  will not be returned by the web service until our new credit system is in
  place.
* `confidence` and `lastSeen` were added to the `Device` response model.

0.5.0 (2016-05-23)
------------------

* Added support for the minFraud Factors.
* Added IP address risk to the minFraud Score model.
* Implement `JsonSerializable`.
* Added the following new values to the payment processor validation:
  `ccnow`, `dalpay`, `epay` (replaces `epayeu`), `payplus`, `pinpayments`,
  `quickpay`, and `verepay`.

0.4.0 (2016-01-20)
------------------

* PHP 7 support was added. PHP 5.3 support was dropped.
* Previously an array within an array would incorrectly validate when using
  the `->with*` methods. This now correctly throws a validation exception.
* Added support for new minFraud Insights outputs. These are:
    * `/credit_card/brand`
    * `/credit_card/type`
    * `/device/id`
    * `/email/is_free`
    * `/email/is_high_risk`
* `input` on the `Warning` response model has been replaced with
  `inputPointer`. The latter is a JSON pointer to the input that caused the
  warning.

0.3.0 (2015-08-10)
------------------

* Add new `is_gift` and `has_gift_message` inputs to order object.
* Request keys with `null` values are no longer validated or sent to the web
  service.

0.2.2 (2015-07-21)
------------------

* Updated `maxmind/web-service-common` to version that fixes POST bug.

0.2.1 (2015-06-30)
------------------

* Updated `maxmind/web-service-common` to version with fixes for PHP 5.3 and
  5.4.

0.2.0 (2015-06-29)
------------------

* First beta release.

0.1.0 (2015-06-18)
------------------

* Initial release.
