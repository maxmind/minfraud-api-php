CHANGELOG
=========

0.6.2
------------------

* Added the following new values to the payment processor validation:
  `concept_payments`, `ecomm365`, `orangepay`, and `pacnet_services`.

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
