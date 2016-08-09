<?php

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email address.
 *
 * @property-read boolean|null $isFree This property is true if MaxMind believes
 * that this email is hosted by a free email provider such as Gmail or Yahoo!
 * Mail.
 *
 * @property-read boolean|null $isHighRisk This field is true if MaxMind believes
 * that this email is likely to be used for fraud. Note that this is also
 * factored into the overall risk_score in the response as well.
 *
 */
class Email extends AbstractModel
{
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
        $this->isFree = $this->safeArrayLookup($response['is_free']);
        $this->isHighRisk = $this->safeArrayLookup($response['is_high_risk']);
    }
}
