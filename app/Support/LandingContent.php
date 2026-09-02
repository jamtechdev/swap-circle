<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingContent
{
    public static function sections(): array
    {
        return ['hero', 'bridge', 'features', 'how', 'community', 'products', 'testimonial', 'partners', 'cta', 'app', 'insights', 'footer', 'auth'];
    }

    public static function defaults(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Community Exchange Platform',
                'title' => 'You Care.',
                'title_highlight' => "They're Covered.",
                'subtitle' => 'Swap Circle connects diaspora communities with trusted services, insurance products, and peer-to-peer exchange — so you can support the people who matter most, wherever they are.',
                'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1920&q=80',
                'trust_line_1' => '★★★★★ 4.9/5 from 1,000+ families',
                'trust_line_2' => '✓ Trusted across 10+ countries',
                'stat_1_value' => '1000',
                'stat_1_suffix' => '+',
                'stat_1_label' => 'Happy Families',
                'stat_2_value' => '150',
                'stat_2_suffix' => '+',
                'stat_2_label' => 'Partner Networks',
                'stat_3_value' => '10',
                'stat_3_suffix' => '+',
                'stat_3_label' => 'Countries',
                'stat_4_value' => '24/7',
                'stat_4_suffix' => '',
                'stat_4_label' => 'Support',
                'activity_label' => 'Live platform activity',
            ],
            'bridge' => [
                'title' => 'Bridging diaspora & home communities',
                'text' => 'Send support, purchase coverage, and exchange services seamlessly across borders.',
            ],
            'features' => [
                'eyebrow' => 'Why Swap Circle',
                'title' => 'Everything you need to protect & connect',
                'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&q=80',
                'badge' => 'Trusted by families worldwide',
                'items' => [
                    ['icon' => '🌍', 'title' => 'Borderless Exchange', 'text' => 'Swap currencies and services across communities without friction.'],
                    ['icon' => '🛡️', 'title' => 'Trusted Coverage', 'text' => 'Purchase insurance and protection products for loved ones back home.'],
                    ['icon' => '💚', 'title' => 'Affordable Plans', 'text' => 'Flexible premiums designed for diaspora families.'],
                    ['icon' => '⚡', 'title' => 'Instant Transfers', 'text' => 'Send funds, create swap offers, and track in real time.'],
                ],
            ],
            'how' => [
                'eyebrow' => 'Simple Process',
                'title' => 'How Swap Circle works',
                'subtitle' => 'From sign-up to full coverage in three easy steps — built for busy diaspora families.',
                'steps' => [
                    ['number' => '01', 'icon' => '📋', 'title' => 'Create Your Account', 'text' => 'Sign up as an individual or corporate member. Verify your email in under 2 minutes.'],
                    ['number' => '02', 'icon' => '🔍', 'title' => 'Choose a Product or Offer', 'text' => 'Browse insurance plans, service products, or community swap offers.'],
                    ['number' => '03', 'icon' => '✅', 'title' => 'Pay and they are covered!', 'text' => 'Complete secure payment. Loved ones receive instant coverage confirmation.'],
                ],
            ],
            'community' => [
                'eyebrow' => 'Built for You',
                'title' => 'Why families choose Swap Circle',
                'main_image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80',
                'satellite_images' => [
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80',
                    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80',
                    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&q=80',
                ],
                'items' => [
                    'Transparent pricing with no hidden charges.',
                    'Peer-to-peer swap marketplace for skills and services.',
                    'Multi-currency wallets with live exchange rates.',
                    'Direct messaging, notifications, and 24/7 support.',
                    'Referral rewards — invite friends and earn wallet credits.',
                ],
            ],
            'products' => [
                'eyebrow' => 'Our Offerings',
                'title' => 'Products built for real families',
                'subtitle' => 'From health coverage to task-based services — flexible plans for diaspora needs.',
                'items' => [
                    ['badge' => 'Type A · Health', 'title' => 'Family Health Coverage', 'text' => 'Comprehensive health insurance for beneficiaries back home.', 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80'],
                    ['badge' => 'Type B · Protection', 'title' => 'Beneficiary Protection Plans', 'text' => 'Named beneficiary with full KYC verification and document support.', 'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=80'],
                    ['badge' => 'Type C · Services', 'title' => 'Task & Delivery Services', 'text' => 'Schedule tasks and on-ground support — tracked end to end.', 'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80'],
                ],
            ],
            'testimonial' => [
                'quote' => '"Swap Circle made it effortless to get my parents covered back in Lagos while I\'m in London. The whole process took less than 10 minutes."',
                'name' => 'Amara O.',
                'role' => 'Diaspora member · London, UK',
                'video_image' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&w=900&q=80',
                'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&q=80',
            ],
            'partners' => [
                'eyebrow' => 'Trusted Networks',
                'title' => 'Partnering with industry leaders',
                'subtitle' => 'Coverage, protection, and exchange powered by global insurance & finance networks.',
                'trust_badges' => [
                    'Licensed partners',
                    'Cross-border coverage',
                    'Verified networks',
                ],
                'items' => [
                    ['name' => 'Swap Circle', 'abbr' => 'SC', 'badge' => 'bg-forest text-lime', 'image' => ''],
                    ['name' => 'Allianz', 'abbr' => 'AZ', 'badge' => 'bg-[#003781] text-white', 'image' => ''],
                    ['name' => 'Jubilee', 'abbr' => 'JB', 'badge' => 'bg-[#006747] text-white', 'image' => ''],
                    ['name' => 'Equity Health', 'abbr' => 'EH', 'badge' => 'bg-[#e31837] text-white', 'image' => ''],
                    ['name' => 'Old Mutual', 'abbr' => 'OM', 'badge' => 'bg-[#009677] text-white', 'image' => ''],
                    ['name' => 'AIG', 'abbr' => 'AIG', 'badge' => 'bg-[#007dba] text-white', 'image' => ''],
                ],
            ],
            'cta' => [
                'title' => 'Start protecting your family today',
                'text' => 'Join thousands of diaspora families who trust Swap Circle for coverage, exchange, and community support.',
                'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&q=80',
            ],
            'app' => [
                'eyebrow' => 'Mobile Ready',
                'title' => 'Manage everything from one platform',
                'text' => 'Wallets, swap offers, product purchases, messaging, and claims — all accessible from your dashboard on any device.',
                'rows' => [
                    '💱 Swap Offer · USD → NGN',
                    '🛡️ Health Plan · Active',
                    '💬 3 new messages',
                    '📊 Wallet · $2,450.00',
                ],
                'cta_row' => '+ Create Swap Offer',
                'toast_1' => '✓ Transfer sent!',
                'toast_2' => 'New swap request',
            ],
            'insights' => [
                'eyebrow' => 'Insights',
                'title' => 'Latest from our community',
                'posts' => [
                    ['date' => 'Aug 28, 2026', 'title' => '5 Things to Know Before Buying Health Coverage Abroad', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80'],
                    ['date' => 'Aug 15, 2026', 'title' => 'How Swap Offers Help Diaspora Families Save More', 'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80'],
                    ['date' => 'Jul 30, 2026', 'title' => 'Building Trust in Community Exchange Platforms', 'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80'],
                ],
            ],
            'footer' => [
                'tagline' => 'A community exchange platform for services. Connect, exchange, and grow together — across borders and generations.',
                'newsletter_text' => 'Stay updated with product news and community stories.',
                'instagram_url' => '',
                'facebook_url' => '',
                'x_url' => '',
            ],
            'auth' => [
                'image' => 'users/assets/images/Rocket_Boy_Flatline.png',
                'eyebrow' => 'Community Exchange Platform',
                'title' => 'Trade services.<br>Build connections.',
                'text' => 'Swap Circle brings people together to exchange skills, services, and opportunities inside one trusted community.',
            ],
        ];
    }

    public static function all(): array
    {
        $content = self::defaults();

        try {
            $stored = DB::table('system_settings')
                ->whereIn('type', collect(self::sections())->map(fn ($s) => 'landing_' . $s)->all())
                ->pluck('description', 'type');

            foreach (self::sections() as $section) {
                $type = 'landing_' . $section;
                if (!$stored->has($type)) {
                    continue;
                }

                $decoded = json_decode($stored[$type], true);
                if (is_array($decoded)) {
                    $content[$section] = array_replace_recursive($content[$section], $decoded);
                }
            }
        } catch (\Throwable $e) {
            // Use defaults when DB is unavailable.
        }

        return $content;
    }

    public static function saveFromRequest(Request $request): void
    {
        $uploadDir = public_path('uploads/landing');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach (self::sections() as $section) {
            $payload = $request->input($section, []);
            if (!is_array($payload)) {
                continue;
            }

            $current = self::all()[$section] ?? [];
            $merged = array_replace_recursive($current, $payload);

            foreach ($request->allFiles() as $field => $file) {
                $pathKey = self::fileFieldToPath($section, $field);
                if ($pathKey === null) {
                    continue;
                }

                self::setNestedValue($merged, $pathKey, self::storeUpload($file, $uploadDir));
            }

            self::upsertSection($section, $merged);
        }
    }

    public static function seedDefaults(): void
    {
        foreach (self::defaults() as $section => $data) {
            self::upsertSection($section, $data);
        }
    }

    public static function assetUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    protected static function upsertSection(string $section, array $data): void
    {
        DB::table('system_settings')->updateOrInsert(
            ['type' => 'landing_' . $section],
            ['description' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
        );
    }

    protected static function storeUpload($file, string $uploadDir): string
    {
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . time()
            . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return 'uploads/landing/' . $filename;
    }

    protected static function fileFieldToPath(string $section, string $field): ?string
    {
        if (!Str::startsWith($field, $section . '__')) {
            return null;
        }

        $path = Str::after($field, $section . '__');

        // PHP converts dots in multipart field names to underscores.
        if (preg_match('/^satellite_images_(\d+)$/', $path, $matches)) {
            return 'satellite_images.' . $matches[1];
        }

        if (preg_match('/^items_(\d+)_image$/', $path, $matches)) {
            return 'items.' . $matches[1] . '.image';
        }

        if (preg_match('/^posts_(\d+)_image$/', $path, $matches)) {
            return 'posts.' . $matches[1] . '.image';
        }

        return $path;
    }

    public static function setNestedValue(array &$target, string $path, mixed $value): void
    {
        $parts = explode('.', $path);
        $ref = &$target;

        foreach ($parts as $index => $part) {
            if ($index === count($parts) - 1) {
                $ref[$part] = $value;
                return;
            }

            if (!isset($ref[$part]) || !is_array($ref[$part])) {
                $ref[$part] = [];
            }

            $ref = &$ref[$part];
        }
    }
}
