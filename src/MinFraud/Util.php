<?php

declare(strict_types=1);

namespace MaxMind\MinFraud;

/**
 * This is an internal class. It is not subject to our public API versioning
 * policy.
 *
 * @ignore
 */
class Util
{
    private static $typoDomains = [
        // gmail.com
        '35gmai.com' => 'gmail.com',
        '636gmail.com' => 'gmail.com',
        'gamil.com' => 'gmail.com',
        'gmail.comu' => 'gmail.com',
        'gmial.com' => 'gmail.com',
        'gmil.com' => 'gmail.com',
        'yahoogmail.com' => 'gmail.com',
        // outlook.com
        'putlook.com' => 'outlook.com',
    ];

    /**
     * @ignore
     */
    public static function maybeHashEmail(array $values): array
    {
        if (!isset($values['email']['address'])) {
            return $values;
        }

        $address = trim(strtolower($values['email']['address']));

        $atIdx = strrpos($address, '@');
        if ($atIdx === false) {
            return $values;
        }

        $domain = self::cleanDomain(substr($address, $atIdx + 1));
        $localPart = substr($address, 0, $atIdx);

        if ($domain !== '' && !isset($values['email']['domain'])) {
            $values['email']['domain'] = $domain;
        }

        $values['email']['address'] = self::hashEmail($localPart, $domain);

        return $values;
    }

    private static function cleanDomain(string $domain): string
    {
        $domain = rtrim(trim($domain), '.');
        if (!$domain) {
            return $domain;
        }
        $domain = idn_to_ascii($domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);

        return isset(self::$typoDomains[$domain]) ? self::$typoDomains[$domain] : $domain;
    }

    private static function hashEmail(string $localPart, string $domain): string
    {
        // Strip off aliased part of email address
        $divider = $domain === 'yahoo.com' ? '-' : '+';

        $aliasIdx = strpos($localPart, $divider);
        if ($aliasIdx) {
            $localPart = substr($localPart, 0, $aliasIdx);
        }

        return md5("$localPart@$domain");
    }
}
