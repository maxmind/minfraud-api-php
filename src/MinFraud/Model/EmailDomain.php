<?php

declare(strict_types=1);

namespace MaxMind\MinFraud\Model;

/**
 * Model containing information about the email domain.
 *
 * @property-read string|null $firstSeen A date string (e.g. 2017-04-24) to
 * identify the date an email domain was first seen by MaxMind. This is
 * expressed using the ISO 8601 date format.
 */
class EmailDomain extends AbstractModel
{
    /**
     * @internal
     *
     * @var string|null
     */
    protected $firstSeen;

    public function __construct(?array $response, array $locales = ['en'])
    {
        parent::__construct($response, $locales);
        $this->firstSeen = $this->safeArrayLookup($response['first_seen']);
    }
}
