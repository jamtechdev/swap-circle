<?php

namespace App\Support;

class LandingContentAudit
{
    /** Expected homepage element counts (must match CMS field groups). */
    public const EXPECTED_COUNTS = [
        'hero_stats' => 4,
        'community_avatars' => 4,
        'community_bullets' => 5,
        'features_cards' => 4,
        'how_steps' => 3,
        'product_cards' => 3,
        'partner_logos' => 6,
        'partner_trust_badges' => 3,
        'insight_posts' => 3,
        'app_rows' => 4,
    ];

    public static function communityAvatarCount(array $content): int
    {
        $main = !empty($content['community']['main_image']) ? 1 : 0;
        $satellites = count(array_filter(
            $content['community']['satellite_images'] ?? [],
            fn ($url) => (string) $url !== ''
        ));

        return $main + $satellites;
    }

    /**
     * @return array{passed: bool, checks: array<int, array{name: string, passed: bool, detail: string}>}
     */
    public static function run(): array
    {
        $content = LandingContent::defaults();
        $checks = [];

        $checks[] = self::check('13 CMS sections defined', count(LandingContent::sections()) === 13);

        foreach (LandingContent::sections() as $section) {
            $checks[] = self::check("Defaults: {$section}", isset($content[$section]));
        }

        $checks[] = self::check(
            'Hero: 4 stats on homepage',
            self::EXPECTED_COUNTS['hero_stats'] === 4
                && isset($content['hero']['stat_1_value'], $content['hero']['stat_4_value'])
        );

        $checks[] = self::check(
            'Hero: activity_label in defaults',
            !empty($content['hero']['activity_label'])
        );

        $checks[] = self::check(
            'Community: 4 avatars (1 main + 3 satellites)',
            self::communityAvatarCount($content) === self::EXPECTED_COUNTS['community_avatars'],
            'Configured ' . self::communityAvatarCount($content) . ' avatars'
        );

        $checks[] = self::check(
            'Community: 3 satellite slots in defaults',
            count($content['community']['satellite_images'] ?? []) === 3
        );

        $arrayChecks = [
            'community.items' => 'community_bullets',
            'features.items' => 'features_cards',
            'how.steps' => 'how_steps',
            'products.items' => 'product_cards',
            'partners.items' => 'partner_logos',
            'partners.trust_badges' => 'partner_trust_badges',
            'insights.posts' => 'insight_posts',
            'app.rows' => 'app_rows',
        ];

        foreach ($arrayChecks as $path => $key) {
            [$section, $field] = explode('.', $path);
            $count = count($content[$section][$field] ?? []);
            $expected = self::EXPECTED_COUNTS[$key];
            $checks[] = self::check(
                ucfirst($section) . ".{$field}: {$count} items (expected {$expected})",
                $count === $expected
            );
        }

        $checks[] = self::check(
            'App: cta_row + 2 toasts in defaults',
            !empty($content['app']['cta_row'])
                && !empty($content['app']['toast_1'])
                && !empty($content['app']['toast_2'])
        );

        $checks[] = self::check(
            'Auth: hero illustration in defaults',
            !empty($content['auth']['image'])
        );

        $checks[] = self::check(
            'Auth layout uses CMS image via assetUrl',
            str_contains(self::readFile('views/layout/auth/master.blade.php'), "LandingContent::assetUrl(\$authImage)")
        );

        $checks[] = self::check(
            'CMS form has auth image field',
            str_contains(self::readCmsForm(), 'name="auth[image]"')
        );

        $blade = self::readBlade();
        $cms = self::readCmsForm();

        $bladeWiring = [
            "content['hero']['activity_label']" => 'Hero activity label wired',
            "content['community']['main_image']" => 'Community main_image wired',
            "content['community']['satellite_images']" => 'Community satellite_images wired',
            "content['partners']['trust_badges']" => 'Partners trust_badges wired',
            "content['app']['cta_row']" => 'App CTA row wired',
            "content['app']['toast_1']" => 'App toast 1 wired',
            "content['app']['toast_2']" => 'App toast 2 wired',
            '[1, 2, 3, 4] as $n' => 'Hero loops all 4 stats',
        ];

        foreach ($bladeWiring as $needle => $label) {
            $checks[] = self::check("Landing blade: {$label}", str_contains($blade, $needle));
        }

        $hardcoded = [
            'Live platform activity' => 'Hardcoded activity label removed',
            'Licensed partners' => 'Hardcoded trust badge removed',
            '+ Create Swap Offer' => 'Hardcoded app CTA removed',
            '✓ Transfer sent!' => 'Hardcoded toast 1 removed',
            'New swap request' => 'Hardcoded toast 2 removed',
        ];

        foreach ($hardcoded as $needle => $label) {
            $checks[] = self::check("Landing blade: {$label}", !str_contains($blade, $needle));
        }

        $cmsWiring = [
            'Community avatars — 4 images' => 'CMS labels 4 community avatars',
            'name="community[main_image]"' => 'CMS has main_image field',
            'name="hero[activity_label]"' => 'CMS has activity_label field',
            'name="partners[trust_badges]' => 'CMS has trust_badges fields',
            'name="app[cta_row]"' => 'CMS has app cta_row field',
            'name="app[toast_1]"' => 'CMS has app toast_1 field',
            'landing-cms-count' => 'CMS shows homepage element counts',
        ];

        foreach ($cmsWiring as $needle => $label) {
            $checks[] = self::check("CMS form: {$label}", str_contains($cms, $needle));
        }

        $passed = !in_array(false, array_column($checks, 'passed'), true);

        return ['passed' => $passed, 'checks' => $checks];
    }

    protected static function check(string $name, bool $passed, string $detail = ''): array
    {
        return compact('name', 'passed', 'detail');
    }

    protected static function readBlade(): string
    {
        return self::readFile('views/landing/index.blade.php');
    }

    protected static function readFile(string $relativePath): string
    {
        $path = resource_path($relativePath);

        return is_file($path) ? file_get_contents($path) : '';
    }

    protected static function readCmsForm(): string
    {
        $path = resource_path('views/admin/landing_page.blade.php');

        return is_file($path) ? file_get_contents($path) : '';
    }
}
