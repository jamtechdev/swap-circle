<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class LegalContent
{
    public static function defaults(): array
    {
        return [
            'cookie_banner_text' => 'We use essential cookies to keep Swap Circle secure and working. With your consent, we may also use optional cookies to improve your experience. Read our Cookie Policy and GDPR information to learn more.',
            'cookie_text' => <<<'HTML'
<h2>Cookie Policy</h2>
<p>Swap Circle uses cookies and similar technologies to provide a secure, reliable platform for diaspora families.</p>
<h3>Essential cookies</h3>
<p>These are required for login sessions, security (CSRF protection), and core platform functionality. They cannot be disabled while using the service.</p>
<h3>Preference cookies</h3>
<p>We store your cookie consent choice locally in your browser so we do not ask you on every visit.</p>
<h3>Analytics cookies (optional)</h3>
<p>If enabled in the future, these help us understand how the platform is used so we can improve features. They are only activated when you choose <strong>Accept all</strong>.</p>
<h3>Managing cookies</h3>
<p>You can change your preference at any time by clearing site data in your browser or using the cookie banner when it reappears. For questions, contact <a href="mailto:support@swapcircle.trade">support@swapcircle.trade</a>.</p>
HTML,
            'gdpr_text' => <<<'HTML'
<h2>GDPR &amp; Data Protection</h2>
<p>Swap Circle is committed to protecting your personal data in line with the UK GDPR and applicable data protection laws.</p>
<h3>Who we are</h3>
<p>Swap Circle operates a community exchange platform connecting diaspora families with products, services, and peer-to-peer exchange. For data protection enquiries, contact <a href="mailto:support@swapcircle.trade">support@swapcircle.trade</a>.</p>
<h3>What we collect</h3>
<ul>
<li>Account details (name, email, phone, address)</li>
<li>Identity and verification documents you upload</li>
<li>Transaction, purchase, claim, and wallet activity</li>
<li>Technical data such as IP address and session identifiers</li>
</ul>
<h3>Why we process your data</h3>
<ul>
<li>To create and manage your account (contract)</li>
<li>To deliver products, claims, and support (contract &amp; legitimate interest)</li>
<li>To meet legal and regulatory obligations (legal obligation)</li>
<li>To improve platform security and prevent fraud (legitimate interest)</li>
</ul>
<h3>Your rights</h3>
<p>You may request access, correction, deletion, restriction, portability, or object to certain processing. You may also withdraw consent where processing is consent-based. To exercise your rights, email <a href="mailto:support@swapcircle.trade">support@swapcircle.trade</a>.</p>
<h3>Retention</h3>
<p>We retain personal data only as long as needed for the purposes above, including legal, accounting, and insurance requirements.</p>
<h3>International transfers</h3>
<p>Where data is transferred across borders, we apply appropriate safeguards consistent with applicable law.</p>
HTML,
        ];
    }

    public static function get(string $type): string
    {
        $defaults = self::defaults();

        try {
            $row = DB::table('system_settings')->where('type', $type)->first();
            if ($row && trim((string) $row->description) !== '') {
                return (string) $row->description;
            }
        } catch (\Throwable $e) {
            // fall through to defaults
        }

        return $defaults[$type] ?? '';
    }

    public static function bannerText(): string
    {
        return self::get('cookie_banner_text');
    }

    public static function seedDefaults(): void
    {
        foreach (self::defaults() as $type => $description) {
            DB::table('system_settings')->updateOrInsert(
                ['type' => $type],
                ['description' => $description]
            );
        }
    }
}
