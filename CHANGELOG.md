CHANGELOG
=========

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
