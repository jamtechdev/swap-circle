<?php

namespace App\Support;

class EmailStyles
{
    public static function head(): string
    {
        $cssPath = resource_path('css/email.css');
        $css = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        return '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>' . $css . '</style>';
    }

    public static function wrap(string $bodyHtml): string
    {
        return '<!DOCTYPE html><html lang="en"><head>' . self::head() . '</head><body><div class="email-shell">'
            . self::brandBar()
            . $bodyHtml
            . self::legalFooter()
            . '</div></body></html>';
    }

    public static function brandBar(): string
    {
        $name = htmlspecialchars(config('app.name', 'Swap Circle'));

        return '<div class="email-brand"><span class="email-brand-mark">SC</span><span class="email-brand-name">' . $name . '</span></div>';
    }

    public static function legalFooter(): string
    {
        $privacy = htmlspecialchars(url('/privacy'));
        $terms = htmlspecialchars(url('/terms'));
        $cookies = htmlspecialchars(url('/cookies'));
        $gdpr = htmlspecialchars(url('/gdpr'));
        $year = date('Y');
        $name = htmlspecialchars(config('app.name', 'Swap Circle'));

        return '<div class="email-legal-footer">'
            . '<p class="email-legal-links">'
            . '<a href="' . $privacy . '">Privacy</a> · '
            . '<a href="' . $terms . '">Terms</a> · '
            . '<a href="' . $cookies . '">Cookies</a> · '
            . '<a href="' . $gdpr . '">GDPR</a>'
            . '</p>'
            . '<p class="email-legal-copy">&copy; ' . $year . ' ' . $name . '. You received this email because you use our platform.</p>'
            . '<p class="email-legal-copy">Questions? <a href="mailto:support@swapcircle.trade">support@swapcircle.trade</a></p>'
            . '</div>';
    }

    public static function card(string $title, string $subtitle, string $bodyHtml): string
    {
        return '<div class="email-card">'
            . '<div class="email-card-header"><h1>' . htmlspecialchars($title) . '</h1>'
            . ($subtitle !== '' ? '<p>' . htmlspecialchars($subtitle) . '</p>' : '')
            . '</div>'
            . '<div class="email-card-body">' . $bodyHtml . '</div>'
            . '</div>';
    }

    public static function button(string $text, string $url): string
    {
        return '<a href="' . htmlspecialchars($url) . '" class="email-btn">' . htmlspecialchars($text) . '</a>';
    }

    public static function receipt(string $greetingHtml, string $receiptTitle, string $tableHtml, string $extraHtml = ''): string
    {
        return '<div class="email-text-fullwidth">' . $greetingHtml . '</div>'
            . '<div class="receipt-container"><h2>' . htmlspecialchars($receiptTitle) . '</h2><table>' . $tableHtml . '</table></div>'
            . ($extraHtml !== '' ? $extraHtml : '')
            . '<div class="email-signoff">Regards,<br><strong>Swap Circle Team</strong></div>';
    }

    public static function downloadBlock(string $invoiceUrl): string
    {
        if ($invoiceUrl === '') {
            return '';
        }

        return '<div class="email-download">'
            . '<p class="email-download-title">Download your documents</p>'
            . '<a href="' . htmlspecialchars($invoiceUrl) . '" class="email-download-btn">Download Invoice</a>'
            . '<p class="email-download-note">If the button does not work, copy and paste the link into your browser.</p>'
            . '</div>';
    }
}
