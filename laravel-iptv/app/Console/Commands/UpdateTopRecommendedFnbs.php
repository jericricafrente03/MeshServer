<?php

namespace App\Console\Commands;

use App\Models\General\Body\Guests\GuestBilling;
use App\Models\General\Body\Hospitality\TopRecommendedFnb;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateTopRecommendedFnbs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-top-recommended-fnbs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define last month's first date
        $recommendationMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();

        // Check if records already exist for the month
        if (TopRecommendedFnb::where('recommended_month', $recommendationMonth)->exists()) {
            $this->info('Records for this month already exist. Skipping update.');
            return 0; // Successful but no changes made
        }

        // Get popular FNB items from last month
        $popularItems = GuestBilling::select('item_id', DB::raw('COUNT(*) as popularity_score'))
            ->whereRaw('DATE_FORMAT(transaction_datetime, "%Y-%m") = ?', [Carbon::now()->subMonth()->format('Y-m')])
            ->where('category', 'fnb')
            ->groupBy('item_id')
            ->orderByDesc('popularity_score')
            ->get();

        // Insert or update records using Eloquent
        foreach ($popularItems as $item) {
            TopRecommendedFnb::updateOrCreate(
                [
                    'fnb_id' => $item->item_id,
                    'recommended_month' => $recommendationMonth,
                ],
                [
                    'popularity_score' => $item->popularity_score,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->info('Top recommended FNBs updated successfully.');
        return 0; // Success
    }
}
