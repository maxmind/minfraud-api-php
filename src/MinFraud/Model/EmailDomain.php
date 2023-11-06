<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email domain.
 */
class EmailDomain implements \JsonSerializable
{
    /**
     * @var string|null A date string (e.g. 2017-04-24) to
     *                  identify the date an email domain was first seen by MaxMind. This is
     *                  expressed using the ISO 8601 date format.
     */
    public readonly ?string $firstSeen;

    public function __construct(?array $response)
    {
        $this->firstSeen = $response['first_seen'] ?? null;
    }

    public function jsonSerialize(): array
    {
        $js = [];

        if ($this->firstSeen !== null) {
            $js['first_seen'] = $this->firstSeen;
        }

        return $js;
    }
}
