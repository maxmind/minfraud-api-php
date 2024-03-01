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
    /**
     * @var array<string, string>
     */
    private static $typoDomains = [
        // gmail.com
        'gmai.com' => 'gmail.com',
        'gamil.com' => 'gmail.com',
        'gmali.com' => 'gmail.com',
        'gmial.com' => 'gmail.com',
        'gmil.com' => 'gmail.com',
        'gmaill.com' => 'gmail.com',
        'gmailm.com' => 'gmail.com',
        'gmailo.com' => 'gmail.com',
        'gmailyhoo.com'  => 'gmail.com',
        'yahoogmail.com' => 'gmail.com',
        // outlook.com
        'putlook.com' => 'outlook.com',
    ];

    /**
     * @var array<string, string>
     */
    private static $equivalentDomains = [
        'googlemail.com' => 'gmail.com',
        'pm.me' => 'protonmail.com',
        'proton.me' => 'protonmail.com',
        'yandex.by' => 'yandex.ru',
        'yandex.com' => 'yandex.ru',
        'yandex.kz' => 'yandex.ru',
        'yandex.ua' => 'yandex.ru',
        'ya.ru' => 'yandex.ru',
    ];

    /**
     * @var array<string, bool>
     */
    private static $fastmailDomains = [
        '123mail.org' => true,
        '150mail.com' => true,
        '150ml.com' => true,
        '16mail.com' => true,
        '2-mail.com' => true,
        '4email.net' => true,
        '50mail.com' => true,
        'airpost.net' => true,
        'allmail.net' => true,
        'bestmail.us' => true,
        'cluemail.com' => true,
        'elitemail.org' => true,
        'emailcorner.net' => true,
        'emailengine.net' => true,
        'emailengine.org' => true,
        'emailgroups.net' => true,
        'emailplus.org' => true,
        'emailuser.net' => true,
        'eml.cc' => true,
        'f-m.fm' => true,
        'fast-email.com' => true,
        'fast-mail.org' => true,
        'fastem.com' => true,
        'fastemail.us' => true,
        'fastemailer.com' => true,
        'fastest.cc' => true,
        'fastimap.com' => true,
        'fastmail.cn' => true,
        'fastmail.co.uk' => true,
        'fastmail.com' => true,
        'fastmail.com.au' => true,
        'fastmail.de' => true,
        'fastmail.es' => true,
        'fastmail.fm' => true,
        'fastmail.fr' => true,
        'fastmail.im' => true,
        'fastmail.in' => true,
        'fastmail.jp' => true,
        'fastmail.mx' => true,
        'fastmail.net' => true,
        'fastmail.nl' => true,
        'fastmail.org' => true,
        'fastmail.se' => true,
        'fastmail.to' => true,
        'fastmail.tw' => true,
        'fastmail.uk' => true,
        'fastmail.us' => true,
        'fastmailbox.net' => true,
        'fastmessaging.com' => true,
        'fea.st' => true,
        'fmail.co.uk' => true,
        'fmailbox.com' => true,
        'fmgirl.com' => true,
        'fmguy.com' => true,
        'ftml.net' => true,
        'h-mail.us' => true,
        'hailmail.net' => true,
        'imap-mail.com' => true,
        'imap.cc' => true,
        'imapmail.org' => true,
        'inoutbox.com' => true,
        'internet-e-mail.com' => true,
        'internet-mail.org' => true,
        'internetemails.net' => true,
        'internetmailing.net' => true,
        'jetemail.net' => true,
        'justemail.net' => true,
        'letterboxes.org' => true,
        'mail-central.com' => true,
        'mail-page.com' => true,
        'mailandftp.com' => true,
        'mailas.com' => true,
        'mailbolt.com' => true,
        'mailc.net' => true,
        'mailcan.com' => true,
        'mailforce.net' => true,
        'mailftp.com' => true,
        'mailhaven.com' => true,
        'mailingaddress.org' => true,
        'mailite.com' => true,
        'mailmight.com' => true,
        'mailnew.com' => true,
        'mailsent.net' => true,
        'mailservice.ms' => true,
        'mailup.net' => true,
        'mailworks.org' => true,
        'ml1.net' => true,
        'mm.st' => true,
        'myfastmail.com' => true,
        'mymacmail.com' => true,
        'nospammail.net' => true,
        'ownmail.net' => true,
        'petml.com' => true,
        'postinbox.com' => true,
        'postpro.net' => true,
        'proinbox.com' => true,
        'promessage.com' => true,
        'realemail.net' => true,
        'reallyfast.biz' => true,
        'reallyfast.info' => true,
        'rushpost.com' => true,
        'sent.as' => true,
        'sent.at' => true,
        'sent.com' => true,
        'speedpost.net' => true,
        'speedymail.org' => true,
        'ssl-mail.com' => true,
        'swift-mail.com' => true,
        'the-fastest.net' => true,
        'the-quickest.com' => true,
        'theinternetemail.com' => true,
        'veryfast.biz' => true,
        'veryspeedy.net' => true,
        'warpmail.net' => true,
        'xsmail.com' => true,
        'yepmail.net' => true,
        'your-mail.com' => true,
    ];

    /**
     * @var array<string, bool>
     */
    private static $yahooDomains = [
        'y7mail.com' => true,
        'yahoo.at' => true,
        'yahoo.be' => true,
        'yahoo.bg' => true,
        'yahoo.ca' => true,
        'yahoo.cl' => true,
        'yahoo.co.id' => true,
        'yahoo.co.il' => true,
        'yahoo.co.in' => true,
        'yahoo.co.kr' => true,
        'yahoo.co.nz' => true,
        'yahoo.co.th' => true,
        'yahoo.co.uk' => true,
        'yahoo.co.za' => true,
        'yahoo.com' => true,
        'yahoo.com.ar' => true,
        'yahoo.com.au' => true,
        'yahoo.com.br' => true,
        'yahoo.com.co' => true,
        'yahoo.com.hk' => true,
        'yahoo.com.hr' => true,
        'yahoo.com.mx' => true,
        'yahoo.com.my' => true,
        'yahoo.com.pe' => true,
        'yahoo.com.ph' => true,
        'yahoo.com.sg' => true,
        'yahoo.com.tr' => true,
        'yahoo.com.tw' => true,
        'yahoo.com.ua' => true,
        'yahoo.com.ve' => true,
        'yahoo.com.vn' => true,
        'yahoo.cz' => true,
        'yahoo.de' => true,
        'yahoo.dk' => true,
        'yahoo.ee' => true,
        'yahoo.es' => true,
        'yahoo.fi' => true,
        'yahoo.fr' => true,
        'yahoo.gr' => true,
        'yahoo.hu' => true,
        'yahoo.ie' => true,
        'yahoo.in' => true,
        'yahoo.it' => true,
        'yahoo.lt' => true,
        'yahoo.lv' => true,
        'yahoo.nl' => true,
        'yahoo.no' => true,
        'yahoo.pl' => true,
        'yahoo.pt' => true,
        'yahoo.ro' => true,
        'yahoo.se' => true,
        'yahoo.sk' => true,
        'ymail.com' => true,
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
        if (\function_exists('idn_to_ascii')) {
            $ascii_domain = idn_to_ascii($domain, \IDNA_NONTRANSITIONAL_TO_ASCII, \INTL_IDNA_VARIANT_UTS46);

            // idn_to_ascii returns false on failure
            if ($ascii_domain !== false) {
                $domain = $ascii_domain;
            }
        }

        $domain = preg_replace('/(?:\.com){2,}$/', '.com', $domain);
        $domain = preg_replace('/\.com[^.]+$/', '.com', $domain);
        $domain = preg_replace('/(?:\.(?:com|c[a-z]{1,2}m|co[ln]|[dsvx]o[mn]|))$/', '.com', $domain);
        $domain = preg_replace('/^\d+(?:gmail?\.com)$/', 'gmail.com', $domain);

        if (isset(self::$typoDomains[$domain])) {
            $domain = self::$typoDomains[$domain];
        }

        if (isset(self::$equivalentDomains[$domain])) {
            $domain = self::$equivalentDomains[$domain];
        }

        return $domain;
    }

    private static function hashEmail(string $localPart, string $domain): string
    {
        // Strip off aliased part of email address
        $divider = isset(self::$yahooDomains[$domain]) ? '-' : '+';

        $aliasIdx = strpos($localPart, $divider);
        if ($aliasIdx) {
            $localPart = substr($localPart, 0, $aliasIdx);
        }

        if ($domain === 'gmail.com') {
            $localPart = str_replace('.', '', $localPart);
        }

        $domainParts = explode('.', $domain);
        if (\count($domainParts) > 2) {
            $possibleDomain = implode('.', \array_slice($domainParts, 1));
            if (isset(self::$fastmailDomains[$possibleDomain])) {
                $domain = $possibleDomain;
            }
            if ($localPart !== '') {
                $localPart = $domainParts[0];
            }
        }

        return md5("$localPart@$domain");
    }

    public static function cleanCreditCard(array $values): array
    {
        if (isset($values['last_4_digits'])) {
            @trigger_error('last_4_digits has been deprecated in favor of last_digits', \E_USER_DEPRECATED);
            $values['last_digits'] = $values['last_4_digits'];
            unset($values['last_4_digits']);
        }

        return $values;
    }
}
