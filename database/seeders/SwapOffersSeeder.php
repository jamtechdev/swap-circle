<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds marketplace swap offers + wallets so the portal dashboard
 * (All Offers / Favorite / My Offers) has visible cards for QA.
 *
 * Run: php artisan db:seed --class=SwapOffersSeeder
 */
class SwapOffersSeeder extends Seeder
{
    /** Stable marker so re-runs replace demo rows without wiping real offers */
    private const SEED_EXPIRY = '2099-12-31 23:59:59';

    public function run(): void
    {
        $dean = DB::table('users_customers')
            ->where('email', 'jtplartisan@gmail.com')
            ->orWhere(function ($q) {
                $q->where('first_name', 'Dean')->where('last_name', 'James');
            })
            ->first();

        if (!$dean) {
            $this->command?->error('Dean James user not found. Log in once or create the account first.');
            return;
        }

        $deanId = (int) $dean->users_customers_id;

        $others = DB::table('users_customers')
            ->where('status', 'Active')
            ->where('users_customers_id', '!=', $deanId)
            ->orderBy('users_customers_id')
            ->limit(4)
            ->pluck('users_customers_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($others) < 2) {
            $this->command?->error('Need at least 2 other active users for All Offers.');
            return;
        }

        $usd = $this->currencyId('USD');
        $eur = $this->currencyId('EUR');
        $gbp = $this->currencyId('GBP');
        $ngn = $this->currencyId('NGN');

        if (!$usd || !$eur || !$gbp || !$ngn) {
            $this->command?->error('Missing USD/EUR/GBP/NGN in system_currencies.');
            return;
        }

        $adminShare = (string) (DB::table('system_settings')->where('type', 'admin_share')->value('description') ?? '2.50');
        $now = Carbon::now();

        // Clean previous seeder rows (offers + favorites tied to them)
        $oldOfferIds = DB::table('swap_offers')
            ->where('expiry_date_time', self::SEED_EXPIRY)
            ->pluck('swap_offers_id');

        if ($oldOfferIds->isNotEmpty()) {
            DB::table('swap_offers_favourite')->whereIn('swap_offers_id', $oldOfferIds)->delete();
            DB::table('swap_offers_requests')->whereIn('swap_offers_id', $oldOfferIds)->delete();
            DB::table('swap_offers')->whereIn('swap_offers_id', $oldOfferIds)->delete();
        }

        // Wallets — All Offers only shows offers whose "to" currency Dean holds
        foreach ([$deanId, ...$others] as $userId) {
            $this->ensureWallet($userId, $usd, 5000);
            $this->ensureWallet($userId, $eur, 3500);
            $this->ensureWallet($userId, $gbp, 2500);
            $this->ensureWallet($userId, $ngn, 2500000);
        }

        // —— My Offers (owned by Dean) ——
        $myOffers = [
            [
                'from' => $gbp, 'to' => $ngn,
                'from_amount' => 250.00, 'to_amount' => 485000.00,
                'rate' => 1940.00, 'base' => 250.00, 'days_ago' => 1,
            ],
            [
                'from' => $eur, 'to' => $usd,
                'from_amount' => 400.00, 'to_amount' => 432.00,
                'rate' => 1.08, 'base' => 400.00, 'days_ago' => 2,
            ],
            [
                'from' => $usd, 'to' => $ngn,
                'from_amount' => 150.00, 'to_amount' => 232500.00,
                'rate' => 1550.00, 'base' => 150.00, 'days_ago' => 0,
            ],
        ];

        $deanOfferIds = [];
        foreach ($myOffers as $row) {
            $deanOfferIds[] = DB::table('swap_offers')->insertGetId([
                'users_customers_id' => $deanId,
                'from_system_currencies_id' => $row['from'],
                'to_system_currencies_id' => $row['to'],
                'from_amount' => $row['from_amount'],
                'to_amount' => $row['to_amount'],
                'admin_share' => $adminShare,
                'admin_share_amount' => 0,
                'exchange_rate' => $row['rate'],
                'system_currencies_id' => $usd,
                'base_amount' => $row['base'],
                'expiry_date_time' => self::SEED_EXPIRY,
                'date_added' => $now->copy()->subDays($row['days_ago'])->subHours(3),
                'status' => 'Pending',
            ]);
        }

        // Pending request on first My Offer (so request count shows)
        if (!empty($deanOfferIds) && !empty($others[0])) {
            DB::table('swap_offers_requests')->insert([
                'swap_offers_id' => $deanOfferIds[0],
                'from_users_customers_id' => $others[0],
                'status' => 'Pending',
                'date_added' => $now->copy()->subHours(5),
            ]);
        }

        // —— All Offers (from other users; "to" matches Dean wallets) ——
        $allOffers = [
            [
                'owner' => $others[0], 'from' => $ngn, 'to' => $gbp,
                'from_amount' => 1000000.00, 'to_amount' => 520.00,
                'rate' => 0.00052, 'base' => 620.00, 'days_ago' => 0,
            ],
            [
                'owner' => $others[0], 'from' => $usd, 'to' => $eur,
                'from_amount' => 300.00, 'to_amount' => 276.00,
                'rate' => 0.92, 'base' => 300.00, 'days_ago' => 1,
            ],
            [
                'owner' => $others[1], 'from' => $eur, 'to' => $gbp,
                'from_amount' => 500.00, 'to_amount' => 425.00,
                'rate' => 0.85, 'base' => 500.00, 'days_ago' => 3,
            ],
            [
                'owner' => $others[1], 'from' => $gbp, 'to' => $usd,
                'from_amount' => 200.00, 'to_amount' => 254.00,
                'rate' => 1.27, 'base' => 200.00, 'days_ago' => 4,
            ],
            [
                'owner' => $others[min(2, count($others) - 1)], 'from' => $usd, 'to' => $ngn,
                'from_amount' => 100.00, 'to_amount' => 155000.00,
                'rate' => 1550.00, 'base' => 100.00, 'days_ago' => 2,
            ],
        ];

        $allOfferIds = [];
        foreach ($allOffers as $row) {
            $allOfferIds[] = DB::table('swap_offers')->insertGetId([
                'users_customers_id' => $row['owner'],
                'from_system_currencies_id' => $row['from'],
                'to_system_currencies_id' => $row['to'],
                'from_amount' => $row['from_amount'],
                'to_amount' => $row['to_amount'],
                'admin_share' => $adminShare,
                'admin_share_amount' => 0,
                'exchange_rate' => $row['rate'],
                'system_currencies_id' => $usd,
                'base_amount' => $row['base'],
                'expiry_date_time' => self::SEED_EXPIRY,
                'date_added' => $now->copy()->subDays($row['days_ago'])->subHours(2),
                'status' => 'Pending',
            ]);
        }

        // —— Favorites (Dean likes 2 marketplace offers) ——
        foreach (array_slice($allOfferIds, 0, 2) as $offerId) {
            DB::table('swap_offers_favourite')->updateOrInsert(
                [
                    'users_customers_id' => $deanId,
                    'swap_offers_id' => $offerId,
                ],
                [
                    'added_date' => $now,
                    'status' => 'Active',
                ]
            );
        }

        $this->command?->info(sprintf(
            'Seeded marketplace for %s (id %d): %d my offers, %d all offers, 2 favorites, wallets USD/EUR/GBP/NGN.',
            $dean->email,
            $deanId,
            count($deanOfferIds),
            count($allOfferIds)
        ));
    }

    private function currencyId(string $code): ?int
    {
        $prefer = match ($code) {
            'USD' => 2,
            'EUR' => 11,
            'GBP' => 19,
            'NGN' => 76,
            default => null,
        };

        if ($prefer && DB::table('system_currencies')->where(['system_currencies_id' => $prefer, 'code' => $code, 'status' => 'Active'])->exists()) {
            return $prefer;
        }

        $id = DB::table('system_currencies')
            ->where('code', $code)
            ->where('status', 'Active')
            ->orderBy('system_currencies_id')
            ->value('system_currencies_id');

        return $id ? (int) $id : null;
    }

    private function ensureWallet(int $userId, int $currencyId, float $amount): void
    {
        $existing = DB::table('users_customers_wallets')
            ->where([
                'users_customers_id' => $userId,
                'system_currencies_id' => $currencyId,
            ])
            ->first();

        if ($existing) {
            DB::table('users_customers_wallets')
                ->where('users_customers_wallets_id', $existing->users_customers_wallets_id)
                ->update([
                    'wallet_amount' => max((float) $existing->wallet_amount, $amount),
                    'status' => 'Active',
                    'date_modified' => Carbon::now(),
                ]);
            return;
        }

        DB::table('users_customers_wallets')->insert([
            'users_customers_id' => $userId,
            'system_currencies_id' => $currencyId,
            'wallet_amount' => $amount,
            'date_added' => Carbon::now(),
            'date_modified' => Carbon::now(),
            'status' => 'Active',
        ]);
    }
}
