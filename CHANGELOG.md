CHANGELOG
=========

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
