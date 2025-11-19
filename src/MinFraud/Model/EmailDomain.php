<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email domain.
 */
class EmailDomain implements \JsonSerializable
{
    /**
     * @var string|null The classification of the email domain. Possible values are:
     *                  - `business` - Business domain
     *                  - `education` - Educational institution domain
     *                  - `government` - Government domain
     *                  - `isp_email` - ISP email provider domain
     */
    public readonly ?string $classification;

    /**
     * @var string|null A date string (e.g. 2017-04-24) to
     *                  identify the date an email domain was first seen by MaxMind. This is
     *                  expressed using the ISO 8601 date format.
     */
    public readonly ?string $firstSeen;

    /**
     * @var float|null The risk associated with the email domain. The value ranges
     *                 from 0.01 to 99. A higher score indicates higher risk.
     */
    public readonly ?float $risk;

    /**
     * @var EmailDomainVisit an object containing information about an automated
     *                       visit to the email domain
     */
    public readonly EmailDomainVisit $visit;

    /**
     * @var float|null This field indicates how much activity is seen on the email
     *                 domain across the minFraud network, expressed in sightings per
     *                 million. The value ranges from 0.001 to 1,000,000.
     */
    public readonly ?float $volume;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->classification = $response['classification'] ?? null;
        $this->firstSeen = $response['first_seen'] ?? null;
        $this->risk = $response['risk'] ?? null;
        $this->visit = new EmailDomainVisit($response['visit'] ?? null);
        $this->volume = $response['volume'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->classification !== null) {
            $js['classification'] = $this->classification;
        }

        if ($this->firstSeen !== null) {
            $js['first_seen'] = $this->firstSeen;
        }

        if ($this->risk !== null) {
            $js['risk'] = $this->risk;
        }

        $visit = $this->visit->jsonSerialize();
        if (!empty($visit)) {
            $js['visit'] = $visit;
        }

        if ($this->volume !== null) {
            $js['volume'] = $this->volume;
        }

        return $js;
    }
}
