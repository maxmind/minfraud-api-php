<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email address.
 */
class Email implements \JsonSerializable
{
    /**
     * @var EmailDomain an object containing information about the email domain
     */
    public readonly EmailDomain $domain;

    /**
     * @var string|null A date string (e.g. 2017-04-24) to
     *                  identify the date an email address was first seen by MaxMind. This is
     *                  expressed using the ISO 8601 date format.
     */
    public readonly ?string $firstSeen;

    /**
     * @var bool|null Whether this email address is from
     *                a disposable email provider. The value will be `null` when no email address
     *                or email domain has been passed as an input.
     */
    public readonly ?bool $isDisposable;

    /**
     * @var bool|null this property is true if MaxMind believes
     *                that this email is hosted by a free email provider such as Gmail or Yahoo!
     *                Mail
     */
    public readonly ?bool $isFree;

    /**
     * @var bool|null This field is true if MaxMind believes
     *                that this email is likely to be used for fraud. Note that this is also
     *                factored into the overall risk_score in the response as well.
     */
    public readonly ?bool $isHighRisk;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->domain = new EmailDomain($response['domain'] ?? null);
        $this->firstSeen = $response['first_seen'] ?? null;
        $this->isDisposable = $response['is_disposable'] ?? null;
        $this->isFree = $response['is_free'] ?? null;
        $this->isHighRisk = $response['is_high_risk'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        $domain = $this->domain->jsonSerialize();
        if (!empty($domain)) {
            $js['domain'] = $domain;
        }

        if ($this->firstSeen !== null) {
            $js['first_seen'] = $this->firstSeen;
        }

        if ($this->isDisposable !== null) {
            $js['is_disposable'] = $this->isDisposable;
        }

        if ($this->isFree !== null) {
            $js['is_free'] = $this->isFree;
        }

        if ($this->isHighRisk !== null) {
            $js['is_high_risk'] = $this->isHighRisk;
        }

        return $js;
    }
}
