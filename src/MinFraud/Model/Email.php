<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email address.
 *
 * @property-read \MaxMind\MinFraud\Model\EmailDomain $domain An object
 * containing information about the email domain.
 * @property-read string|null $firstSeen A date string (e.g. 2017-04-24) to
 * identify the date an email address was first seen by MaxMind. This is
 * expressed using the ISO 8601 date format.
 * @property-read bool|null $isDisposable Whether this email address is from
 * a disposable email provider. The value will be `null` when no email address
 * or email domain has been passed as an input.
 * @property-read bool|null $isFree This property is true if MaxMind believes
 * that this email is hosted by a free email provider such as Gmail or Yahoo!
 * Mail.
 * @property-read bool|null $isHighRisk This field is true if MaxMind believes
 * that this email is likely to be used for fraud. Note that this is also
 * factored into the overall risk_score in the response as well.
 */
class Email extends AbstractModel
{
    /**
     * @internal
     */
    protected $domain;

    /**
     * @internal
     */
    protected $firstSeen;

    /**
     * @internal
     */
    protected $isDisposable;

    /**
     * @internal
     */
    protected $isFree;

    /**
     * @internal
     */
    protected $isHighRisk;

    public function __construct($response, $locales = ['en'])
    {
        parent::__construct($response, $locales);

        $this->domain = new EmailDomain($this->safeArrayLookup($response['domain']));
        $this->firstSeen = $this->safeArrayLookup($response['first_seen']);
        $this->isDisposable = $this->safeArrayLookup($response['is_disposable']);
        $this->isFree = $this->safeArrayLookup($response['is_free']);
        $this->isHighRisk = $this->safeArrayLookup($response['is_high_risk']);
    }
}
