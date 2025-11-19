<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about an automated visit to the email domain.
 *
 * This object provides data from MaxMind's automated checks of domain
 * accessibility, including whether the domain is live, has errors, or
 * redirects to another location.
 */
class EmailDomainVisit implements \JsonSerializable
{
    /**
     * @var bool|null This field is present with a value of `true` if the domain
     *                redirects to another URL. If the domain does not redirect,
     *                this field will be `null`.
     */
    public readonly ?bool $hasRedirect;

    /**
     * @var string|null The date when MaxMind last checked the domain's
     *                  accessibility. This is expressed using the ISO 8601 date
     *                  format (YYYY-MM-DD), e.g., "2025-11-15".
     */
    public readonly ?string $lastVisitedOn;

    /**
     * @var string|null The status of the domain based on the most recent
     *                  automated check. Possible values are:
     *                  - `live` - Domain is accessible and functioning
     *                  - `dns_error` - Domain has DNS resolution issues
     *                  - `network_error` - Network connectivity issues
     *                  - `http_error` - HTTP request failed
     *                  - `parked` - Domain appears to be parked
     *                  - `pre_development` - Domain appears to be in pre-development
     */
    public readonly ?string $status;

    /**
     * @param array<string, mixed>|null $response
     */
    public function __construct(?array $response)
    {
        $this->hasRedirect = $response['has_redirect'] ?? null;
        $this->lastVisitedOn = $response['last_visited_on'] ?? null;
        $this->status = $response['status'] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->hasRedirect !== null) {
            $js['has_redirect'] = $this->hasRedirect;
        }

        if ($this->lastVisitedOn !== null) {
            $js['last_visited_on'] = $this->lastVisitedOn;
        }

        if ($this->status !== null) {
            $js['status'] = $this->status;
        }

        return $js;
    }
}
