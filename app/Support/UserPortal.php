<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserPortal
{
    public static function successfulPurchaseCount(int $userId): int
    {
        return (int) DB::table('products_purchases')
            ->where('users_customers_id', $userId)
            ->where('payment_status', 'Successful')
            ->count();
    }

    public static function postAuthRedirectUrl(int $userId): string
    {
        return self::successfulPurchaseCount($userId) > 0
            ? url('/users/dashboard')
            : url('/users/products');
    }

    public static function claimWaitingDays(): int
    {
        try {
            $value = DB::table('system_settings')
                ->where('type', 'claim_waiting_days')
                ->value('description');

            return max(0, (int) ($value ?? 30));
        } catch (\Throwable $e) {
            return 30;
        }
    }

    public static function claimEligibility(object $purchase): array
    {
        if (($purchase->payment_status ?? '') !== 'Successful') {
            return [
                'eligible' => false,
                'reason' => 'This purchase is not eligible for claims yet.',
                'eligible_at' => null,
                'days_remaining' => null,
            ];
        }

        $waitingDays = self::claimWaitingDays();
        $purchaseDate = Carbon::parse($purchase->date_added ?? $purchase->cover_start_date ?? now())->startOfDay();
        $eligibleAt = $purchaseDate->copy()->addDays($waitingDays);
        $today = Carbon::now()->startOfDay();

        if ($today->lt($eligibleAt)) {
            $daysRemaining = $today->diffInDays($eligibleAt);

            return [
                'eligible' => false,
                'reason' => 'Claims open on ' . $eligibleAt->format('d M Y') . " ({$waitingDays} days after purchase).",
                'eligible_at' => $eligibleAt,
                'days_remaining' => $daysRemaining,
            ];
        }

        return [
            'eligible' => true,
            'reason' => null,
            'eligible_at' => $eligibleAt,
            'days_remaining' => 0,
        ];
    }

    public static function loadUserPurchases(int $userId, bool $successfulOnly = false): Collection
    {
        $query = DB::table('products_purchases')
            ->where('users_customers_id', $userId)
            ->orderBy('products_purchases_id', 'DESC');

        if ($successfulOnly) {
            $query->where('payment_status', 'Successful');
        }

        $purchases = $query->get();

        foreach ($purchases as $purchase) {
            $purchase->product = DB::table('products')
                ->where('products_id', $purchase->products_id)
                ->first();

            if (!$purchase->product) {
                continue;
            }

            if (in_array($purchase->product->type, ['A', 'B'], true)) {
                $purchase->beneficiary = DB::table('products_purchases_beneficiaries')
                    ->where('products_purchases_id', $purchase->products_purchases_id)
                    ->first();

                if ($purchase->beneficiary) {
                    $purchase->beneficiary->occupation = DB::table('occupations')
                        ->where('occupations_id', $purchase->beneficiary->occupations_id)
                        ->value('name');

                    $purchase->beneficiary->relationship = DB::table('relationships')
                        ->where('relationships_id', $purchase->beneficiary->relationships_id)
                        ->value('name');
                }
            }

            $purchase->claim_eligibility = self::claimEligibility($purchase);
        }

        return $purchases;
    }

    public static function loadLatestPurchasesForClaims(int $userId): Collection
    {
        $purchases = DB::table('products_purchases')
            ->where('users_customers_id', $userId)
            ->where('payment_status', 'Successful')
            ->whereIn('products_purchases_id', function ($query) use ($userId) {
                $query->select(DB::raw('MAX(products_purchases_id)'))
                    ->from('products_purchases')
                    ->where('users_customers_id', $userId)
                    ->where('payment_status', 'Successful')
                    ->groupBy('products_id');
            })
            ->orderBy('products_purchases_id', 'ASC')
            ->get();

        foreach ($purchases as $purchase) {
            $purchase->product = DB::table('products')
                ->where('products_id', $purchase->products_id)
                ->first();

            $purchase->claim_eligibility = self::claimEligibility($purchase);
            $purchase->existing_claim = DB::table('products_purchases_claims')
                ->where('products_purchases_id', $purchase->products_purchases_id)
                ->exists();
        }

        return $purchases;
    }
}
