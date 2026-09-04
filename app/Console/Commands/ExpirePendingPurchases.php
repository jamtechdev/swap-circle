<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpirePendingPurchases extends Command
{
    protected $signature = 'purchases:expire-pending
                            {--hours= : Hours after which Pending purchases expire}
                            {--dry-run : Show count without updating}';

    protected $description = 'Expire abandoned Pending product purchases older than N hours';

    public function handle(): int
    {
        $hours = (int) ($this->option('hours') ?: env('PURCHASE_PENDING_EXPIRE_HOURS', 24));
        if ($hours < 1) {
            $hours = 24;
        }

        $cutoff = Carbon::now()->subHours($hours)->format('Y-m-d H:i:s');

        $query = DB::table('products_purchases')
            ->where('payment_status', 'Pending')
            ->where('date_added', '<', $cutoff);

        $count = (clone $query)->count();
        $this->info("Pending purchases older than {$hours}h: {$count}");

        if ($this->option('dry-run') || $count === 0) {
            return self::SUCCESS;
        }

        $update = [
            'payment_status' => 'Expired',
            'payment_message' => "Auto-expired after {$hours} hours unpaid",
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $updated = $query->update($update);
        $this->info("Marked {$updated} purchase(s) as Expired.");

        return self::SUCCESS;
    }
}
