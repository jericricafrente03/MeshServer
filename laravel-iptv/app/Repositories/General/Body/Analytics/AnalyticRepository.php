<?php

namespace App\Repositories\General\Body\Analytics;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Analytics\IAnalyticRepository;
use App\Models\Analytics\Analytic;
use App\Models\General\Body\Guests\Guest;
use App\Models\General\Body\Guests\GuestBilling;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\FnbCategory;
use App\Models\General\Body\Hospitality\HospitalityItem;
use App\Models\General\Body\Hospitality\HospitalityService;
use App\Models\General\Body\Messages\BroadcastMessage;
use App\Models\General\Body\Messages\MessageRecipient;
use App\Models\General\Body\Messages\RegularMessage;
use App\Models\General\Body\Tv\TvChannel;
use App\Models\General\Body\Tv\TvChannelCategory;
use App\Models\GeneralStatus;
use App\Models\SystemSettings\DeviceAdb\AdbSetting;
use App\Models\SystemSettings\ThemeManager\DefaultApp;
use Carbon\Carbon;

class AnalyticRepository extends Controller implements IAnalyticRepository
{
    public function getApps($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get all default apps ordered by ID
        $apps = DefaultApp::orderBy('id')->where('is_enable', 1)->get(['id', 'name', 'color']);

        // Fetch analytics data grouped by app and month
        $analyticsData = Analytic::selectRaw("
                item_id,
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(counter) as total_count
            ")
            ->where('type_id', 51)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('item_id', 'month')
            ->orderBy('month', 'asc') // Ensure months are in ascending order
            ->get();

        // Generate last 12 months list (current month first, going backward)
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Convert to month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        // Group analytics data by item_id
        $groupedData = $analyticsData->groupBy('item_id');

        $formattedData = [];
        $colors = [];

        foreach ($apps as $app) {
            // Initialize an array with 0 for each month
            $dataPoints = $months->mapWithKeys(fn ($month) => [$month => 0]);

            // Fill in actual values
            if (isset($groupedData[$app->id])) {
                foreach ($groupedData[$app->id] as $record) {
                    $dataPoints[$record->month] = (int) $record->total_count;
                }
            }

            // Keep data in correct order (current month first)
            $formattedData[] = [
                'name' => $app->name,
                'data' => array_values($dataPoints->toArray()),
            ];

            // Store colors in the same order as apps
            $colors[] = $app->color;
        }
        return response()->json([
            'result' => 'success',
            'data' => $formattedData,
            'month' => $monthNames,
            'color' => $colors // No names, just ordered list
        ]);
    }

    public function getYearCheckin($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Fetch analytics data grouped by month
        $analyticsData = RoomAssignment::selectRaw("
                DATE_FORMAT(check_in, '%Y-%m') as month,
                COUNT(id) as total_count
            ")
            ->where('is_checkout', 1) // Only check-out records
            ->whereBetween('check_in', [$startMonth, $endMonth]) // Only last 12 months
            ->groupBy('month')
            ->orderBy('month', 'desc') // Ensure months are ordered from latest to oldest
            ->pluck('total_count', 'month'); // Get an associative array [ 'YYYY-MM' => count ]

        // Generate last 12 months list (current month first, going backward)
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Generate final data array with counts (fill 0 for missing months)
        $data = $months->map(fn ($m) => $analyticsData[$m] ?? 0)->toArray();

        // Convert to short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();
        
        return response()->json([
            'result' => 'success',
            'data' => $data,
            'month' => $monthNames
        ]);
    }

    public function getRooms($request)
    {   
        // $statuses = GeneralStatus::where('category', 'room_status')->orderBy('sort')->get(['id', 'name', 'bg_color', 'sort']);

        // $data = Room::selectRaw("
        //     COUNT(id) as total_count
        // ")->with('status')->get();
        
        // $groupedData = $data->groupBy('room_status');
        
        // $formattedData = [];
        // $colors = [];
        // $count = 0;
        // foreach ($statuses as $status) {
        //     // Fill in actual values
        //     if (isset($groupedData[$status->id])) {
        //         foreach ($groupedData[$status->id] as $record) {
        //             $count = (int) $record->total_count;
        //         }
        //     }
    
        //     // Keep data in correct order (current month first)
        //     $formattedData[] = [
        //         'name' => $status->name,
        //         'count' => $count,
        //     ];
    
        //     // Store colors in the same order as apps
        //     $colors[] = $status->bg_color;
        // }

        // return response()->json([
        //     'result' => 'success',
        //     'data' => $formattedData,
        //     'color' => $colors // No names, just ordered list
        // ]);

        $statuses = GeneralStatus::where('category', 'room_status')
            ->orderBy('sort')
            ->get(['id', 'name', 'bg_color', 'sort']);

        // Fetch room count grouped by `room_status`
        $roomCounts = Room::selectRaw("room_status, COUNT(id) as total_count")
            ->groupBy('room_status')
            ->pluck('total_count', 'room_status')
            ->toArray(); // Convert collection to array

        // Get total room count
        $totalRooms = array_sum($roomCounts);

        // Avoid division by zero
        $totalRooms = $totalRooms > 0 ? $totalRooms : 1; 

        $formattedData = [];
        $colors = [];

        foreach ($statuses as $status) {
            $count = (int) ($roomCounts[$status->id] ?? 0); // Default to 0 if no rooms found
            $percentage = round(($count / $totalRooms) * 100, 2); // Calculate percentage

            $formattedData[] = [
                'name' => $status->name,
                'count' => (int) ($roomCounts[$status->id] ?? 0), // Default to 0 if no rooms found
                'color' => $status->bg_color,
                'percentage' => $percentage
            ];
            $colors[] = $status->bg_color; // Store colors in the same order as statuses
        }

        return response()->json([
            'result' => 'success',
            'data' => $formattedData,
            'color' => $colors // No names, just ordered list
        ]);
    }

    public function getGuests($request)
    {
        $checkinCount = RoomAssignment::where('is_checkout', 0)->count();
        $checkoutCount = RoomAssignment::where('is_checkout', 1)
            ->whereDate('check_out', Carbon::today()) // Check-in date is today
            ->count();

        $result = [
            'type' => 'guest',
            'checkin' => $checkinCount,
            'checkout' => $checkoutCount
        ];

        return response()->json([
            'result' => $result,
        ]);
    }

    public function getTop10TvChannels($request)
    {   
        $topTvChannels = Analytic::where('type_id', 52)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->selectRaw('item_id, SUM(counter) as views')
            ->groupBy('item_id')
            ->orderByDesc('views')
            ->limit(10)
            ->with('tvChannel:id,name') // Load channel names
            ->get();

        $data = [];
        $names = [];

        foreach ($topTvChannels as $item) {
            $data[] = (int) $item->views;
            $names[] = $item->tvChannel->name ?? 'Unknown';
        }

        return response()->json([
            'result' => 'success',
            'data' => $data,
            'name' => $names
        ]);
    }

    public function getTop10Fnbs($request)
    {
        // Define date ranges for this month and last month
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topFnbs = GuestBilling::where('category', 'fnb')
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->orderByDesc('sum')
            ->limit(10)
            ->with('fnb:id,name')
            ->get();
        
        // Extract item_ids for querying last month's data
        $topFnbIds = $topFnbs->pluck('item_id')->toArray();

        $lastMonthData = GuestBilling::where('category', 'fnb')
            ->whereIn('item_id', $topFnbIds)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->pluck('sum', 'item_id');

        // Prepare response data
        $thisMonthItems = [];
        $lastMonthItems = [];
        $names = [];

        foreach ($topFnbs as $item) {
            $thisMonthItems[] = (int) $item->sum;
            $lastMonthItems[] = (int) ($lastMonthData[$item->item_id] ?? 0); // Default to 0 if no data
            $names[] = $item->fnb->name ?? 'Unknown';
        }
        // dd($names);
        return response()->json([
            'result' => 'success',
            'data' => [
                'this_month' => $thisMonthItems,
                'last_month' => $lastMonthItems
            ],
            'name' => $names
        ]);
    }

    public function getTop10ItemRequests($request)
    {
        // Define date ranges for this month and last month
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topData = GuestBilling::where('category', 'item_request')
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->orderByDesc('sum')
            ->limit(10)
            ->with('itemRequest:id,name')
            ->get();
        
        // Extract item_ids for querying last month's data
        $topDataIds = $topData->pluck('item_id')->toArray();

        $lastMonthTopData = GuestBilling::where('category', 'item_request')
            ->whereIn('item_id', $topDataIds)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->pluck('sum', 'item_id'); // Get an associative array [item_id => sum]

        // Prepare response data
        $thisMonthData = [];
        $lastMonthData = [];
        $names = [];

        foreach ($topData as $item) {
            $thisMonthData[] = (int) $item->sum;
            $lastMonthData[] = (int) ($lastMonthTopData[$item->item_id] ?? 0); // Default to 0 if no data
            $names[] = $item->itemRequest->name ?? 'Unknown';
        }

        return response()->json([
            'result' => 'success',
            'data' => [
                'this_month' => $thisMonthData,
                'last_month' => $lastMonthData
            ],
            'name' => $names
        ]);
    }

    public function getTop10ServiceRequests($request)
    {
        // Define date ranges for this month and last month
        $startOfThisMonth = Carbon::now()->startOfMonth();
        $endOfThisMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topData = GuestBilling::where('category', 'service_request')
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->orderByDesc('sum')
            ->limit(10)
            ->with('serviceRequest:id,name')
            ->get();
        
        // Extract item_ids for querying last month's data
        $topDataIds = $topData->pluck('item_id')->toArray();

        $lastMonthTopData = GuestBilling::where('category', 'service_request')
            ->whereIn('item_id', $topDataIds)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->selectRaw('item_id, SUM(quantity) as sum')
            ->groupBy('item_id')
            ->pluck('sum', 'item_id'); // Get an associative array [item_id => sum]

        // Prepare response data
        $thisMonthData = [];
        $lastMonthData = [];
        $names = [];

        foreach ($topData as $item) {
            $thisMonthData[] = (int) $item->sum;
            $lastMonthData[] = (int) ($lastMonthTopData[$item->item_id] ?? 0); // Default to 0 if no data
            $names[] = $item->serviceRequest->name ?? 'Unknown';
        }

        return response()->json([
            'result' => 'success',
            'data' => [
                'this_month' => $thisMonthData,
                'last_month' => $lastMonthData
            ],
            'name' => $names
        ]);
    }
    
    public function pingDevices($request)
    {
        $this->pingDeviceAnalytics();
        return response()->json([
            'result' => 'success'
        ]);
    }

    public function getTvChannelData($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();
        

        $tvChannelCategoryCounter = $this->tvChannelCategory($months, $monthNames, $startMonth, $endMonth);
        $tvChannelActivity = $this->tvChannelActivity($months, $monthNames, $startMonth, $endMonth);
        $createdDeletedCount = $this->createdDeletedCount($startMonth, $endMonth);
        $enabledDisabledCount = $this->enabledDisabledCount();
        $topTvChannel = $this->topTvChannels();

        return response()->json([
            'result' => 'success',
            'enable_tv_channel' => $enabledDisabledCount['enabled'],
            'disable_tv_channel' => $enabledDisabledCount['disabled'],
            'created_count' => $createdDeletedCount['created'],
            'deleted_count' => $createdDeletedCount['deleted'],
            'tv_channel_activity' => $tvChannelActivity,
            'category_counter' => $tvChannelCategoryCounter,
            'top_tv_channel' => $topTvChannel
        ]);
    }

    private function topTvChannels()
    {
        $topTvChannelStartOfThisMonth = Carbon::now()->startOfMonth();
        $topTvChannelEndOfThisMonth = Carbon::now()->endOfMonth();

        $topTvChannelStartOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $topTvChannelEndOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Get top channels for this month
        $topTvChannels = Analytic::where('type_id', 52)
            ->whereBetween('created_at', [$topTvChannelStartOfThisMonth, $topTvChannelEndOfThisMonth])
            ->selectRaw('item_id, SUM(counter) as views')
            ->groupBy('item_id')
            ->orderByDesc('views')
            ->with('tvChannel:id,name') // Load channel names
            ->get();

        // Extract item_ids of top channels this month
        $topChannelIds = $topTvChannels->pluck('item_id')->toArray();

        // Get views for last month, but only for channels that appeared this month
        $lastMonthData = Analytic::where('type_id', 52)
            ->whereBetween('created_at', [$topTvChannelStartOfLastMonth, $topTvChannelEndOfLastMonth])
            ->whereIn('item_id', $topChannelIds) // Only for this month’s top channels
            ->selectRaw('item_id, SUM(counter) as views')
            ->groupBy('item_id')
            ->pluck('views', 'item_id') // Get associative array [item_id => views]
            ->toArray();

        // Initialize results
        $topTvChannelData = [];
        $topTvChannelLastMonthData = [];
        $topTvChannelNames = [];

        foreach ($topTvChannels as $item) {
            $topTvChannelData[] = (int) $item->views;
            $topTvChannelLastMonthData[] = (int) ($lastMonthData[$item->item_id] ?? 0); // Use 0 if not found
            $topTvChannelNames[] = $item->tvChannel->name ?? 'Unknown';
        }

        // Final output array
        $data = [
            'this_month' => $topTvChannelData,
            'last_month' => $topTvChannelLastMonthData,
            'name' => $topTvChannelNames,
        ];

        return $data;
    }

    private function enabledDisabledCount()
    {
        $enabledChannel = TvChannel::where('is_enable', 1)->count();
        $disabledChannel = TvChannel::where('is_enable', 0)->count();

        $data = [
            'enabled' => $enabledChannel,
            'disabled' => $disabledChannel
        ];

        return $data;
    }

    private function createdDeletedCount($startMonth, $endMonth)
    {
        $createdCount = TvChannel::whereNull('deleted_at')->whereBetween('created_at', [$startMonth, $endMonth])->count();
        $deletedCount = TvChannel::onlyTrashed()->whereBetween('created_at', [$startMonth, $endMonth])->count();

        $data = [
            'created' => $createdCount,
            'deleted' => $deletedCount
        ];

        return $data;
    }

    private function tvChannelActivity($months, $monthNames, $startMonth, $endMonth)
    {
        // Fetch created and deleted counts per month
        $createdData = TvChannel::selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        $deletedData = TvChannel::onlyTrashed()
            ->selectRaw("
            DATE_FORMAT(deleted_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('deleted_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        // Generate last 12 months (current month first, going backward)
        // $months = collect(range(0, 11))
        //     ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
        //     ->values();

        // Prepare final dataset
        $createdCounts = $months->map(fn ($m) => $createdData[$m] ?? 0)->toArray();
        $deletedCounts = $months->map(fn ($m) => $deletedData[$m] ?? 0)->toArray();
        // $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        $data = [
            'data' => [
                ['name' => 'Created', 'data' => $createdCounts],
                ['name' => 'Deleted', 'data' => $deletedCounts],
            ],
            'months' => $monthNames
        ];

        return $data;
    }

    private function tvChannelCategory($months, $monthNames, $startMonth, $endMonth)
    {
        // Get all TV channel categories (ensuring every category is present)
        $categories = TvChannelCategory::orderBy('order_no', 'asc')->pluck('name')->toArray();

        // Fetch analytics data grouped by month and category (SUM the `counter` column)
        $analyticsData = TvChannel::withTrashed() // Include soft-deleted TV channels
            ->join('tv_channel_categories', 'tv_channels.category_id', '=', 'tv_channel_categories.id')
            ->join('analytics', function ($join) {
                $join->on('tv_channels.id', '=', 'analytics.item_id')
                    ->where('analytics.type_id', 52); // Type 55 for TV
            })
            ->selectRaw("
                tv_channel_categories.name as category,
                DATE_FORMAT(analytics.created_at, '%Y-%m') as month,
                SUM(analytics.counter) as total_count
            ")
            ->whereBetween('analytics.created_at', [Carbon::now()->subMonths(11)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('category', 'month')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('category');

        // Initialize an empty dataset
        $chartData = [];

        // Loop through each category and ensure all months have values
        foreach ($categories as $category) {
            $dataByMonth = isset($analyticsData[$category]) ? $analyticsData[$category]->pluck('total_count', 'month')->toArray() : [];

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartData[] = [
                'name' => $category,
                'data' => $counts
            ];
        }

        // Fetch total counter sum per category, including categories with no analytics records
        $categoryData = TvChannelCategory::leftJoin('tv_channels', 'tv_channels.category_id', '=', 'tv_channel_categories.id')
            ->leftJoin('analytics', function ($join) use ($startMonth, $endMonth) {
                $join->on('tv_channels.id', '=', 'analytics.item_id')
                    ->where('analytics.type_id', 52) // Type 55 for TV
                    ->whereBetween('analytics.created_at', [$startMonth, $endMonth]);
            })
            ->selectRaw("
                tv_channel_categories.name as category,
                COALESCE(SUM(analytics.counter), 0) as total_counter
            ")
            // ->whereBetween('analytics.created_at', [$startMonth, $endMonth])
            ->groupBy('tv_channel_categories.id', 'tv_channel_categories.name')
            ->orderBy('tv_channel_categories.order_no', 'asc') // Sort alphabetically
            ->get();

        // Extract categories and counters
        $categories = $categoryData->pluck('category')->toArray();
        $totalCounters = $categoryData->pluck('total_counter')->map(fn ($count) => (int) $count)->toArray();

        // Generate random colors for each category
        $randomColors = collect($categories)->map(fn () => sprintf("#%06X", mt_rand(0, 0xFFFFFF)))->toArray();

        $data = [
            'data' => $chartData,
            'months' => $monthNames,
            'total_counter' => $totalCounters,
            'category' => $categories,
            'color' => $randomColors
        ];

        return $data;
    }

    public function getTvChannelCounter($request)
    {
        // dd($request->tv_channel_id);
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        // Fetch analytics data grouped by month and channel (SUM the `counter` column)
        $analyticsMonthData = Analytic::join('tv_channels', 'analytics.item_id', '=', 'tv_channels.id')
            ->selectRaw("
                tv_channels.name as channel_name,
                DATE_FORMAT(analytics.created_at, '%Y-%m') as month,
                SUM(analytics.counter) as total_count
            ")
            ->where('analytics.type_id', 52)
            ->where('tv_channels.id', $request->tv_channel_id)
            ->whereBetween('analytics.created_at', [$startMonth, $endMonth])
            ->groupBy('tv_channels.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsMonthData->groupBy('channel_name');

        $chartMonthData = [];

        foreach ($groupedData as $channelName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM' => count ]
            $dataByMonth = $records->pluck('total_count', 'month')->toArray();

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartMonthData[] = [
                'name' => $channelName, // Channel Name
                'data' => $counts // Monthly counts
            ];
        }

        $tvChannelMonthCounter = [
            'data' => $chartMonthData,
            'months' => $monthNames,
        ];

        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        // Fetch analytics data grouped by date and channel (SUM the `counter` column)
        $analyticsDayData = Analytic::join('tv_channels', 'analytics.item_id', '=', 'tv_channels.id')
            ->selectRaw("
                tv_channels.name as channel_name,
                DATE(analytics.created_at) as date,
                SUM(analytics.counter) as total_count
            ")
            ->where('analytics.type_id', 52)
            ->where('tv_channels.id', $request->tv_channel_id)
            ->whereBetween('analytics.created_at', [$startDate, $endDate])
            ->groupBy('tv_channels.name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsDayData->groupBy('channel_name');

        $chartDayData = [];

        foreach ($groupedData as $channelName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM-DD' => count ]
            $dataByDate = $records->pluck('total_count', 'date')->toArray();

            // Ensure all 30 days are present, defaulting to 0 if missing
            $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

            $chartDayData[] = [
                'name' => $channelName, // Channel Name
                'data' => $counts // Daily counts
            ];
        }

        $tvChannelDayCounter = [
            'data' => $chartDayData,
            'date' => $dates->toArray(),
        ];

        return response()->json([
            'result' => 'success',
            'tv_channel_month' => $tvChannelMonthCounter,
            'tv_channel_day' => $tvChannelDayCounter,
        ]);
    }

    public function getRegularMessagingData($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();
        $today = Carbon::now()->toDateString(); // Get today's date

        $typeIds = [21, 22, 23];

        $monthlyPercentageRegularMessageType = $this->monthlyPercentageRegularMessageType();
        $todaysSingleMessages = $this->todaysSingleMessages($today);
        $todaysGroupMessages = $this->todaysGroupMessages($today);
        $thirtyDaysMessages = $this->thirtyDaysMessages();

        $totalDeletedMessages = $this->totalDeletedMessageType();
        $messageTypeTotalCounter = $this->messageType($startMonth, $endMonth, $typeIds);
        $totalStatusCounts = $this->totalStatusCounts();
        $twelveMonthsMessageTypeChartArray = $this->twelveMonthsMessagesTypeData($startMonth, $endMonth, $typeIds);
        
        return response()->json([
            'result' => 'success',
            'thirtyDaysMessages' => $thirtyDaysMessages,
            'todays_group_messages' => $todaysGroupMessages,
            'todays_single_messages' => $todaysSingleMessages,
            'message_type_monthly_percentage' => $monthlyPercentageRegularMessageType,
            'message_type_twelve_months_chart_data' => $twelveMonthsMessageTypeChartArray,
            'message_type_total_counter' => $messageTypeTotalCounter,
            'total_deleted_messages' => $totalDeletedMessages,
            'total_new_messages' => $totalStatusCounts[31] ?? 0,
            'total_delivered_messages' => $totalStatusCounts[32] ?? 0,
            'total_seen_messages' => $totalStatusCounts[33] ?? 0,
        ]);
    }

    private function twelveMonthsMessagesTypeData($startMonth, $endMonth, $typeIds)
    {
        // Fetch counts per month per status
        $twelveMonthsMessageTypeData = RegularMessage::withTrashed()
            ->whereIn('type_id', $typeIds)
            ->selectRaw("type_id, DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total_count")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('type_id', 'month')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('type_id');

        // Get status names and colors
        $twelveMonthsMessageTypeStatuses = GeneralStatus::whereIn('id', $typeIds)->pluck('name', 'id');
        $twelveMonthsMessageTypeColors = GeneralStatus::whereIn('id', $typeIds)->pluck('bg_color')->toArray();
        
        // Generate the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Initialize chart dataset
        $twelveMonthsMessageTypeChartData = [];

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        foreach ($typeIds as $typeId) {
            $dataByMonth = isset($twelveMonthsMessageTypeData[$typeId])
                // ? $twelveMonthsMessageTypeData[$typeId]->pluck('total_count', 'month')->toArray()
                // : [];
                ? collect($twelveMonthsMessageTypeData[$typeId])->pluck('total_count', 'month')->toArray()
                : [];
            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $twelveMonthsMessageTypeChartData[] = [
                'name' => $twelveMonthsMessageTypeStatuses[$typeId] ?? 'Unknown',
                'data' => $counts,
                // Default black if missing
            ];
        }

        // dd($twelveMonthsMessageTypeChartData);

        $data = [
            'data' => $twelveMonthsMessageTypeChartData,
            'months' => $monthNames,
            'colors' => $twelveMonthsMessageTypeColors, 
        ];

        return $data;
    }

    private function totalStatusCounts()
    {
        $data = MessageRecipient::whereHas('message', function ($q) {
            $q->whereNull('deleted_at'); // Ensure regular_messages are not soft deleted
        })
        ->selectRaw('status_id, COUNT(*) as total')
        ->groupBy('status_id')
        ->pluck('total', 'status_id');

        return $data;
    }

    private function messageType($startMonth, $endMonth, $typeIds)
    {
        $typeCounts = RegularMessage::whereIn('type_id', $typeIds)
            ->selectRaw('type_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('type_id')
            ->pluck('total', 'type_id');

        $statuses = GeneralStatus::whereIn('id', $typeIds)
            ->pluck('name', 'id');

        $colors = GeneralStatus::whereIn('id', $typeIds)
            ->pluck('bg_color', 'id');

        $data = [
            'data' => array_map(fn($id) => $typeCounts[$id] ?? 0, $typeIds), // Ensure all type IDs exist
            'name' => array_map(fn($id) => $statuses[$id] ?? 'Unknown', $typeIds),
            'color' => array_map(fn($id) => $colors[$id] ?? '#000000', $typeIds), // Default to black if missing
        ];

        return $data;
    }

    private function totalDeletedMessageType()
    {
        $data = MessageRecipient::whereHas('message', function ($q) {
            $q->onlyTrashed(); // Only select messages that are soft deleted
        })->count();

        return $data;
    }

    private function monthlyPercentageRegularMessageType()
    {
        $typeMappings = [
            21 => 'single',
            22 => 'group',
            23 => 'all'
        ];
        
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Fetch total count per type
        $totalCounts = RegularMessage::withTrashed()
            ->whereIn('type_id', array_keys($typeMappings))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('type_id, COUNT(id) as total_count')
            ->groupBy('type_id')
            ->get()
            ->mapWithKeys(fn ($item) => [$typeMappings[$item->type_id] => (int) $item->total_count])
            ->toArray();
        
        // Calculate total count for the month
        $totalCountForMonth = array_sum($totalCounts);
        
        // Calculate percentage for each type
        $finalData = [];
        foreach ($typeMappings as $type) {
            $count = $totalCounts[$type] ?? 0;
            $percentage = $totalCountForMonth > 0 ? round(($count / $totalCountForMonth) * 100, 2) : 0;
        
            $finalData[$type] = [
                'total_count' => $count,
                'percentage' => $percentage // This is for the pie chart
            ];
        }
        
        return $finalData;
    }

    private function todaysSingleMessages($today)
    {
        // Fetch counts of messages per room
        $roomMessageCounts = MessageRecipient::join('regular_messages', 'message_recipients.regular_message_id', '=', 'regular_messages.id')
            ->join('rooms', 'message_recipients.room_id', '=', 'rooms.id')
            ->where('regular_messages.type_id', 21) // Only Single messages
            ->whereDate('message_recipients.created_at', $today) // Only messages from today
            ->selectRaw('rooms.id as room_id, rooms.name, COUNT(message_recipients.id) as message_count')
            ->groupBy('rooms.id', 'rooms.name')
            ->orderBy('rooms.id') // Sort by room ID
            ->get();

        // Extract data for the chart
        $data = [
            [
                'name' => 'Single Messages',
                'data' => $roomMessageCounts->pluck('message_count')->toArray(),
            ]
        ];

        $rooms = $roomMessageCounts->pluck('name')->toArray();

        $result = [
            'data' => $data,
            'rooms' => $rooms,
        ];

        return $result;
    }

    private function todaysGroupMessages($today)
    {
        // Fetch counts of messages per room
        $roomMessageCounts = RegularMessage::join('device_categories', 'regular_messages.device_category_id', '=', 'device_categories.id')
            ->where('regular_messages.type_id', 22) // Only Single messages
            ->whereDate('regular_messages.created_at', $today) // Only messages from today
            ->selectRaw('device_categories.id as device_category_id, device_categories.name, COUNT(regular_messages.id) as message_count')
            ->groupBy('device_categories.id', 'device_categories.name')
            ->orderBy('device_categories.id') // Sort by room ID
            ->get();

        // Extract data for the chart
        $data = [
            [
                'name' => 'Group Messages',
                'data' => $roomMessageCounts->pluck('message_count')->toArray(),
            ]
        ];

        $groups = $roomMessageCounts->pluck('name')->toArray();

        $result = [
            'data' => $data,
            'group' => $groups,
        ];

        return $result;
    }

    private function thirtyDaysMessages()
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        $counter = [
            'single' => $this->thirtyDaysSingle($startDate, $endDate, $dates),
            'group' => $this->thirtyDaysGroup($startDate, $endDate, $dates),
            'all' => $this->thirtyDaysAll($startDate, $endDate, $dates),
            'days' => $dates->toArray(),
        ];

        return $counter;
    }

    private function thirtyDaysSingle($startDate, $endDate, $dates)
    {
        $data = RegularMessage::withTrashed()
            ->selectRaw("
                DATE(created_at) as date,
                COUNT(id) as total_count
            ")
            ->where('type_id', 21)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->get();


        $dataByDate = $data->pluck('total_count', 'date')->toArray();

        // Ensure all 30 days are present, defaulting to 0 if missing
        $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

        // Final response structure
        $dayCounter = [
            'data' => [
                [
                    'name' => "Single Message",
                    'data' => $counts // Daily counts
                ]
            ],
        ];

        return $dayCounter;
    }

    private function thirtyDaysGroup($startDate, $endDate, $dates)
    {
        // Fetch analytics data grouped by date and channel (SUM the `counter` column)
        $data = RegularMessage::withTrashed()
            ->selectRaw("
                DATE(created_at) as date,
                COUNT(id) as total_count
            ")
            ->where('type_id', 22)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->get();


        $dataByDate = $data->pluck('total_count', 'date')->toArray();

        // Ensure all 30 days are present, defaulting to 0 if missing
        $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

        // Final response structure
        $dayCounter = [
            'data' => [
                [
                    'name' => "Group Message",
                    'data' => $counts // Daily counts
                ]
            ],
        ];

        return $dayCounter;
    }

    private function thirtyDaysAll($startDate, $endDate, $dates)
    {
        $data = RegularMessage::withTrashed()
            ->selectRaw("
                DATE(created_at) as date,
                COUNT(id) as total_count
            ")
            ->where('type_id', 23)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->get();


        $dataByDate = $data->pluck('total_count', 'date')->toArray();

        // Ensure all 30 days are present, defaulting to 0 if missing
        $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

        // Final response structure
        $dayCounter = [
            'data' => [
                [
                    'name' => "Message to All",
                    'data' => $counts // Daily counts
                ]
            ],
        ];

        return $dayCounter;
    }

    public function getBroadcastMessagingData($request)
    {
        $typeIds = [41, 42]; // Group & All
        $broadcastTypeIds = [46, 47, 48]; // Ticker, Advertisement, Emergency

        $typeStatuses = GeneralStatus::whereIn('id', $broadcastTypeIds)->pluck('name', 'id');
        $typeColors = GeneralStatus::whereIn('id', $broadcastTypeIds)->pluck('bg_color', 'id')->toArray();
        
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $thirtyDaysMessages = $this->thirtyDaysBroadcastMessages();
        $broadcastType = $this->broadCastMessagesType($typeIds, $broadcastTypeIds);
        $twelveMonthsBroadcastMessagesTypeData = $this->monthlyBroadcastMessages($broadcastTypeIds, $startMonth, $endMonth, $typeStatuses, $typeColors);
        $broadcastMessagesTypeTotalPercentageCount = $this->percentageBroadcastMessageType($broadcastTypeIds, $startMonth, $endMonth, $typeStatuses, $typeColors);
        
        return response()->json([
            'result' => 'success',
            'broadcastMessagesTypeTotalPercentageCount' => $broadcastMessagesTypeTotalPercentageCount,
            'thirtyDaysMessages' => $thirtyDaysMessages,
            'twelveMonthsBroadcastMessagesTypeData' => $twelveMonthsBroadcastMessagesTypeData,
            'broadcastType' => $broadcastType,
        ]);
    }

    private function percentageBroadcastMessageType($broadcastTypeIds, $startMonth, $endMonth, $typeStatuses, $typeColors)
    {
        // Fetch total count per type
        $result = BroadcastMessage::withTrashed()
            ->whereIn('broadcast_type_id', $broadcastTypeIds)
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->selectRaw('broadcast_type_id, COUNT(id) as total_count')
            ->groupBy('broadcast_type_id')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->broadcast_type_id => [
                'broadcast_type_id' => $item->broadcast_type_id,
                'total_count' => $item->total_count
            ]])
            ->toArray();

        $totalCount = array_sum(array_column($result, 'total_count'));
        
        // Calculate percentage for each type
        $data = [];
        foreach ($broadcastTypeIds as $type) {

            $count = $result[$type]['total_count'] ?? 0;
            $percentage = $totalCount > 0 ? round(($count / $totalCount) * 100, 2) : 0;
        
            $data[$typeStatuses[$type]] = [
                'count' => $count,
                'percentage' => $percentage, // This is for the pie chart
                'color' => $typeColors[$type]
            ];
        }
        
        return $data;
    }

    private function monthlyBroadcastMessages($broadcastTypeIds, $startMonth, $endMonth, $typeStatuses)
    {   
        // Fetch counts per month per status
        $result = BroadcastMessage::withTrashed()
            ->whereIn('broadcast_type_id', $broadcastTypeIds)
            ->selectRaw("broadcast_type_id, DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as total_count")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('broadcast_type_id', 'month')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('broadcast_type_id');

        $typeColors = GeneralStatus::whereIn('id', $broadcastTypeIds)->pluck('bg_color')->toArray();
        
        // Generate the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Initialize chart dataset
        $data = [];

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        foreach ($broadcastTypeIds as $typeId) {
            $dataByMonth = isset($result[$typeId])
    
                ? collect($result[$typeId])->pluck('total_count', 'month')->toArray()
                : [];
            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $data[] = [
                'name' => $typeStatuses[$typeId] ?? 'Unknown',
                'data' => $counts,
                // Default black if missing
            ];
        }

        $twelveMonthsBroacastMessagesTypeChartData = [
            'data' => $data,
            'months' => $monthNames,
            'colors' => $typeColors, 
        ];

        return $twelveMonthsBroacastMessagesTypeChartData;
    }

    private function broadCastMessagesType($typeIds, $broadcastTypeIds)
    {
        // Fetch status names and colors
        $typeNames = GeneralStatus::whereIn('id', $typeIds)->pluck('name', 'id')->toArray();
        $broadcastTypeNames = GeneralStatus::whereIn('id', $broadcastTypeIds)->pluck('name', 'id')->toArray();
        $typeColors = GeneralStatus::whereIn('id', $typeIds)->pluck('bg_color', 'id')->toArray();

        // Initialize result array with default values (0 counts)
        $result = [];
        foreach ($broadcastTypeIds as $broadcastId) {
            $broadcastName = $broadcastTypeNames[$broadcastId] ?? 'Unknown';
            
            $result[$broadcastName] = [
                'data' => [0, 0], // Default counts for Group and All
                'name' => array_values($typeNames), // Ensure "Group" and "All" are in correct order
                'color' => array_values($typeColors) // Ensure colors match
            ];
        }

        // Fetch actual data from broadcast_messages
        $broadcastData = BroadcastMessage::withTrashed()
            ->whereIn('type_id', $typeIds)
            ->whereIn('broadcast_type_id', $broadcastTypeIds)
            ->selectRaw("broadcast_type_id, type_id, COUNT(id) as total_count")
            ->groupBy('broadcast_type_id', 'type_id')
            ->get();

        // Populate the result array with actual values
        foreach ($broadcastData as $item) {
            $broadcastName = $broadcastTypeNames[$item->broadcast_type_id] ?? 'Unknown';
            $typeIndex = array_search($typeNames[$item->type_id] ?? 'Unknown', $result[$broadcastName]['name']);

            if ($typeIndex !== false) {
                $result[$broadcastName]['data'][$typeIndex] = $item->total_count;
            }
        }

        return $result;
    }

    private function thirtyDaysBroadcastMessages()
    {
        $types = [
            46 => 'Ticker',
            47 => 'Advertisement',
            48 => 'Emergency',
        ];
    
        $result = [];

        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        foreach ($types as $typeId => $name) {
            $data = BroadcastMessage::withTrashed()
                ->selectRaw("DATE(created_at) as date, COUNT(id) as total_count")
                ->where('broadcast_type_id', $typeId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupByRaw('DATE(created_at)')
                ->orderBy('date', 'desc')
                ->get();

            $dataByDate = $data->pluck('total_count', 'date')->toArray();

            // Ensure all 30 days are present, defaulting to 0 if missing
            $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

            // Store result in the correct format
            $result[strtolower($name)] = [
                [
                    'name' => $name,
                    'data' => $counts,
                ]
            ];
        }

        $result['days'] = $dates->toArray();

        return $result;
    }

    public function getFnbCounter($request)
    {
        // dd($request->tv_channel_id);
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        $analyticsMonthData = GuestBilling::join('fnbs', 'guest_billings.item_id', '=', 'fnbs.id')
            ->selectRaw("
                fnbs.name as name,
                DATE_FORMAT(guest_billings.created_at, '%Y-%m') as month,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'fnb')
            ->where('fnbs.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startMonth, $endMonth])
            ->groupBy('fnbs.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsMonthData->groupBy('name');

        $chartMonthData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM' => count ]
            $dataByMonth = $records->pluck('total_count', 'month')->toArray();

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartMonthData[] = [
                'name' => $itemName,
                'data' => $counts // Monthly counts
            ];
        }

        $monthCounter = [
            'data' => $chartMonthData,
            'months' => $monthNames,
        ];

        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        $analyticsDayData = GuestBilling::join('fnbs', 'guest_billings.item_id', '=', 'fnbs.id')
            ->selectRaw("
                fnbs.name as name,
                DATE(guest_billings.created_at) as date,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'fnb')
            ->where('fnbs.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startDate, $endDate])
            ->groupBy('fnbs.name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsDayData->groupBy('name');

        $chartDayData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM-DD' => count ]
            $dataByDate = $records->pluck('total_count', 'date')->toArray();

            // Ensure all 30 days are present, defaulting to 0 if missing
            $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

            $chartDayData[] = [
                'name' => $itemName,
                'data' => $counts // Daily counts
            ];
        }

        $dayCounter = [
            'data' => $chartDayData,
            'date' => $dates->toArray(),
        ];

        return response()->json([
            'result' => 'success',
            'item_month' => $monthCounter,
            'item_day' => $dayCounter,
        ]);
    }

    public function getFnbData($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();
        

        $categoryCounter = $this->fnbCategory($months, $monthNames, $startMonth, $endMonth);
        $activity = $this->fnbActivity($months, $monthNames, $startMonth, $endMonth);
        $createdDeletedCount = $this->createdDeletedFnbCount($startMonth, $endMonth);
        $enabledDisabledCount = $this->enabledDisabledFnbCount();
        $topItem = $this->topFnbs();

        return response()->json([
            'result' => 'success',
            'enable_item' => $enabledDisabledCount['enabled'],
            'disable_item' => $enabledDisabledCount['disabled'],
            'created_count' => $createdDeletedCount['created'],
            'deleted_count' => $createdDeletedCount['deleted'],
            'item_activity' => $activity,
            'category_counter' => $categoryCounter,
            'top_item' => $topItem
        ]);
    }

    private function topFnbs()
    {
        $itemStartOfThisMonth = Carbon::now()->startOfMonth();
        $itemEndOfThisMonth = Carbon::now()->endOfMonth();

        $itemStartOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $itemEndOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topItems = GuestBilling::where('category', 'fnb')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfThisMonth, $itemEndOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->orderByDesc('sold')
            ->with('fnb:id,name')
            ->get();

        $topItemIds = $topItems->pluck('item_id')->toArray();


        $lastMonthData = GuestBilling::where('category', 'fnb')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfLastMonth, $itemEndOfLastMonth])
            ->whereIn('item_id', $topItemIds) // Only for this month’s top channels
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->pluck('sold', 'item_id')
            ->toArray();
        
        // Initialize results
        $itemData = [];
        $topItemLastMonthData = [];
        $topItemNames = [];

        foreach ($topItems as $item) {
            $itemData[] = (int) $item->sold;
            $topItemLastMonthData[] = (int) ($lastMonthData[$item->item_id] ?? 0); // Use 0 if not found
            $topItemNames[] = $item->fnb->name ?? 'Unknown';
        }

        // Final output array
        $data = [
            'this_month' => $itemData,
            'last_month' => $topItemLastMonthData,
            'name' => $topItemNames,
        ];

        return $data;
    }

    private function fnbCategory($months, $monthNames, $startMonth, $endMonth)
    {
        $categories = FnbCategory::orderBy('order_no', 'asc')->pluck('name')->toArray();

        // Fetch analytics data grouped by month and category (SUM the `counter` column)
        $analyticsData = Fnb::withTrashed()
            ->join('fnb_categories', 'fnbs.category_id', '=', 'fnb_categories.id')
            ->join('guest_billings', function ($join) {
                $join->on('fnbs.id', '=', 'guest_billings.item_id')
                    ->where('guest_billings.category', 'fnb')
                    ->where('is_paid', 1); 
            })
            ->selectRaw("
                fnb_categories.id as category_id,
                fnb_categories.name as category,
                DATE_FORMAT(guest_billings.created_at, '%Y-%m') as month,
                SUM(guest_billings.quantity) as total_count
            ")
            ->whereBetween('guest_billings.created_at', [Carbon::now()->subMonths(11)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('fnb_categories.id', 'fnb_categories.name', 'month')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('category');

        // Initialize an empty dataset
        $chartData = [];

        // Loop through each category and ensure all months have values
        foreach ($categories as $category) {
            $dataByMonth = isset($analyticsData[$category]) ? $analyticsData[$category]->pluck('total_count', 'month')->toArray() : [];

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartData[] = [
                'name' => $category,
                'data' => $counts
            ];
        }

        // Fetch total counter sum per category, including categories with no analytics records
        $categoryData = FnbCategory::leftJoin('fnbs', 'fnbs.category_id', '=', 'fnb_categories.id')
            ->leftJoin('guest_billings', function ($join) use ($startMonth, $endMonth) {
                $join->on('fnbs.id', '=', 'guest_billings.item_id')
                    ->where('guest_billings.category', 'fnb')
                    ->whereBetween('guest_billings.created_at', [$startMonth, $endMonth])
                    ->where('is_paid', 1);
            })
            ->selectRaw("
                fnb_categories.name as category,
                COALESCE(SUM(guest_billings.quantity), 0) as total_counter
            ")
            ->groupBy('fnb_categories.id', 'fnb_categories.name')
            ->orderBy('fnb_categories.order_no', 'asc') // Sort by order number
            ->get();

        // Extract categories and counters
        $categories = $categoryData->pluck('category')->toArray();
        $totalCounters = $categoryData->pluck('total_counter')->map(fn ($count) => (int) $count)->toArray();
        
        // Generate random colors for each category
        $randomColors = collect($categories)->map(fn () => sprintf("#%06X", mt_rand(0, 0xFFFFFF)))->toArray();

        $data = [
            'data' => $chartData,
            'months' => $monthNames,
            'total_counter' => $totalCounters,
            'category' => $categories,
            'color' => $randomColors
        ];

        return $data;
    }

    private function fnbActivity($months, $monthNames, $startMonth, $endMonth)
    {
        // Fetch created and deleted counts per month
        $createdData = Fnb::selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        $deletedData = Fnb::onlyTrashed()
            ->selectRaw("
            DATE_FORMAT(deleted_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('deleted_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        // Prepare final dataset
        $createdCounts = $months->map(fn ($m) => $createdData[$m] ?? 0)->toArray();
        $deletedCounts = $months->map(fn ($m) => $deletedData[$m] ?? 0)->toArray();

        $data = [
            'data' => [
                ['name' => 'Created', 'data' => $createdCounts],
                ['name' => 'Deleted', 'data' => $deletedCounts],
            ],
            'months' => $monthNames
        ];

        return $data;
    }

    private function enabledDisabledFnbCount()
    {
        $enabledChannel = Fnb::where('is_enable', 1)->count();
        $disabledChannel = Fnb::where('is_enable', 0)->count();

        $data = [
            'enabled' => $enabledChannel,
            'disabled' => $disabledChannel
        ];

        return $data;
    }

    private function createdDeletedFnbCount($startMonth, $endMonth)
    {
        $createdCount = Fnb::whereNull('deleted_at')->whereBetween('created_at', [$startMonth, $endMonth])->count();
        $deletedCount = Fnb::onlyTrashed()->whereBetween('created_at', [$startMonth, $endMonth])->count();

        $data = [
            'created' => $createdCount,
            'deleted' => $deletedCount
        ];

        return $data;
    }

    public function getItemRequestCounter($request)
    {
        // dd($request->tv_channel_id);
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        $analyticsMonthData = GuestBilling::join('hospitality_items', 'guest_billings.item_id', '=', 'hospitality_items.id')
            ->selectRaw("
                hospitality_items.name as name,
                DATE_FORMAT(guest_billings.created_at, '%Y-%m') as month,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'item_request')
            ->where('hospitality_items.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startMonth, $endMonth])
            ->groupBy('hospitality_items.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsMonthData->groupBy('name');

        $chartMonthData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM' => count ]
            $dataByMonth = $records->pluck('total_count', 'month')->toArray();

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartMonthData[] = [
                'name' => $itemName, 
                'data' => $counts 
            ];
        }

        $monthCounter = [
            'data' => $chartMonthData,
            'months' => $monthNames,
        ];

        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        $analyticsDayData = GuestBilling::join('hospitality_items', 'guest_billings.item_id', '=', 'hospitality_items.id')
            ->selectRaw("
                hospitality_items.name as name,
                DATE(guest_billings.created_at) as date,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'item_request')
            ->where('hospitality_items.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startDate, $endDate])
            ->groupBy('hospitality_items.name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsDayData->groupBy('name');

        $chartDayData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM-DD' => count ]
            $dataByDate = $records->pluck('total_count', 'date')->toArray();

            // Ensure all 30 days are present, defaulting to 0 if missing
            $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

            $chartDayData[] = [
                'name' => $itemName,
                'data' => $counts // Daily counts
            ];
        }

        $dayCounter = [
            'data' => $chartDayData,
            'date' => $dates->toArray(),
        ];

        return response()->json([
            'result' => 'success',
            'item_month' => $monthCounter,
            'item_day' => $dayCounter,
        ]);
    }

    public function getItemRequestData($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();
        

        $activity = $this->itemRequestActivity($months, $monthNames, $startMonth, $endMonth);
        $createdDeletedCount = $this->createdDeletedItemRequestCount($startMonth, $endMonth);
        $enabledDisabledCount = $this->enabledDisabledItemRequestCount();
        $topItem = $this->topItemRequests();

        return response()->json([
            'result' => 'success',
            'enable_item' => $enabledDisabledCount['enabled'],
            'disable_item' => $enabledDisabledCount['disabled'],
            'created_count' => $createdDeletedCount['created'],
            'deleted_count' => $createdDeletedCount['deleted'],
            'item_activity' => $activity,
            'top_item' => $topItem
        ]);
    }

    private function topItemRequests()
    {
        $itemStartOfThisMonth = Carbon::now()->startOfMonth();
        $itemEndOfThisMonth = Carbon::now()->endOfMonth();

        $itemStartOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $itemEndOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topItems = GuestBilling::where('category', 'item_request')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfThisMonth, $itemEndOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->orderByDesc('sold')
            ->with('itemRequest:id,name')
            ->get();

        $topItemIds = $topItems->pluck('item_id')->toArray();


        $lastMonthData = GuestBilling::where('category', 'item_request')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfLastMonth, $itemEndOfLastMonth])
            ->whereIn('item_id', $topItemIds) // Only for this month’s top channels
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->pluck('sold', 'item_id')
            ->toArray();
        
        // Initialize results
        $itemData = [];
        $topItemLastMonthData = [];
        $topItemNames = [];

        foreach ($topItems as $item) {
            $itemData[] = (int) $item->sold;
            $topItemLastMonthData[] = (int) ($lastMonthData[$item->item_id] ?? 0); // Use 0 if not found
            $topItemNames[] = $item->itemRequest->name ?? 'Unknown';
        }

        // Final output array
        $data = [
            'this_month' => $itemData,
            'last_month' => $topItemLastMonthData,
            'name' => $topItemNames,
        ];

        return $data;
    }

    private function itemRequestActivity($months, $monthNames, $startMonth, $endMonth)
    {
        // Fetch created and deleted counts per month
        $createdData = HospitalityItem::selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        $deletedData = HospitalityItem::onlyTrashed()
            ->selectRaw("
            DATE_FORMAT(deleted_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('deleted_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        // Prepare final dataset
        $createdCounts = $months->map(fn ($m) => $createdData[$m] ?? 0)->toArray();
        $deletedCounts = $months->map(fn ($m) => $deletedData[$m] ?? 0)->toArray();

        $data = [
            'data' => [
                ['name' => 'Created', 'data' => $createdCounts],
                ['name' => 'Deleted', 'data' => $deletedCounts],
            ],
            'months' => $monthNames
        ];

        return $data;
    }

    private function enabledDisabledItemRequestCount()
    {
        $enabledChannel = HospitalityItem::where('is_enable', 1)->count();
        $disabledChannel = HospitalityItem::where('is_enable', 0)->count();

        $data = [
            'enabled' => $enabledChannel,
            'disabled' => $disabledChannel
        ];

        return $data;
    }

    private function createdDeletedItemRequestCount($startMonth, $endMonth)
    {
        $createdCount = HospitalityItem::whereNull('deleted_at')->whereBetween('created_at', [$startMonth, $endMonth])->count();
        $deletedCount = HospitalityItem::onlyTrashed()->whereBetween('created_at', [$startMonth, $endMonth])->count();

        $data = [
            'created' => $createdCount,
            'deleted' => $deletedCount
        ];

        return $data;
    }

    public function getServiceRequestCounter($request)
    {
        // dd($request->tv_channel_id);
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();

        $analyticsMonthData = GuestBilling::join('hospitality_services', 'guest_billings.item_id', '=', 'hospitality_services.id')
            ->selectRaw("
                hospitality_services.name as name,
                DATE_FORMAT(guest_billings.created_at, '%Y-%m') as month,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'service_request')
            ->where('hospitality_services.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startMonth, $endMonth])
            ->groupBy('hospitality_services.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsMonthData->groupBy('name');

        $chartMonthData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM' => count ]
            $dataByMonth = $records->pluck('total_count', 'month')->toArray();

            // Ensure all 12 months are present, defaulting to 0 if missing
            $counts = $months->map(fn ($m) => $dataByMonth[$m] ?? 0)->toArray();

            $chartMonthData[] = [
                'name' => $itemName, 
                'data' => $counts 
            ];
        }

        $monthCounter = [
            'data' => $chartMonthData,
            'months' => $monthNames,
        ];

        $startDate = Carbon::now()->subDays(29)->startOfDay(); // 30 days ago (including today)
        $endDate = Carbon::now()->endOfDay();

        // Get the last 30 days dynamically
        $dates = collect(range(0, 29))
            ->map(fn ($i) => Carbon::now()->subDays($i)->format('Y-m-d'))
            ->values();

        $analyticsDayData = GuestBilling::join('hospitality_services', 'guest_billings.item_id', '=', 'hospitality_services.id')
            ->selectRaw("
                hospitality_services.name as name,
                DATE(guest_billings.created_at) as date,
                SUM(guest_billings.quantity) as total_count
            ")
            ->where('is_paid', 1)
            ->where('guest_billings.category', 'service_request')
            ->where('hospitality_services.id', $request->item_id)
            ->whereBetween('guest_billings.created_at', [$startDate, $endDate])
            ->groupBy('hospitality_services.name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        // Group data by channel
        $groupedData = $analyticsDayData->groupBy('name');

        $chartDayData = [];

        foreach ($groupedData as $itemName => $records) {
            // Convert collection to an associative array [ 'YYYY-MM-DD' => count ]
            $dataByDate = $records->pluck('total_count', 'date')->toArray();

            // Ensure all 30 days are present, defaulting to 0 if missing
            $counts = $dates->map(fn ($d) => $dataByDate[$d] ?? 0)->toArray();

            $chartDayData[] = [
                'name' => $itemName,
                'data' => $counts // Daily counts
            ];
        }

        $dayCounter = [
            'data' => $chartDayData,
            'date' => $dates->toArray(),
        ];

        return response()->json([
            'result' => 'success',
            'item_month' => $monthCounter,
            'item_day' => $dayCounter,
        ]);
    }

    public function getServiceRequestData($request)
    {
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // Get the last 12 months dynamically
        $months = collect(range(0, 11))
            ->map(fn ($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();

        // Get short month names
        $monthNames = $months->map(fn ($m) => Carbon::parse($m . '-01')->format('M'))->toArray();
        

        $activity = $this->serviceRequestActivity($months, $monthNames, $startMonth, $endMonth);
        $createdDeletedCount = $this->createdDeletedServiceRequestCount($startMonth, $endMonth);
        $enabledDisabledCount = $this->enabledDisabledServiceRequestCount();
        $topItem = $this->topServiceRequests();

        return response()->json([
            'result' => 'success',
            'enable_item' => $enabledDisabledCount['enabled'],
            'disable_item' => $enabledDisabledCount['disabled'],
            'created_count' => $createdDeletedCount['created'],
            'deleted_count' => $createdDeletedCount['deleted'],
            'item_activity' => $activity,
            'top_item' => $topItem
        ]);
    }

    private function topServiceRequests()
    {
        $itemStartOfThisMonth = Carbon::now()->startOfMonth();
        $itemEndOfThisMonth = Carbon::now()->endOfMonth();

        $itemStartOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $itemEndOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $topItems = GuestBilling::where('category', 'service_request')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfThisMonth, $itemEndOfThisMonth])
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->orderByDesc('sold')
            ->with('serviceRequest:id,name')
            ->get();

        $topItemIds = $topItems->pluck('item_id')->toArray();


        $lastMonthData = GuestBilling::where('category', 'service_request')
            ->where('is_paid', 1)
            ->whereBetween('created_at', [$itemStartOfLastMonth, $itemEndOfLastMonth])
            ->whereIn('item_id', $topItemIds) // Only for this month’s top channels
            ->selectRaw('item_id, SUM(quantity) as sold')
            ->groupBy('item_id')
            ->pluck('sold', 'item_id')
            ->toArray();
        
        // Initialize results
        $itemData = [];
        $topItemLastMonthData = [];
        $topItemNames = [];

        foreach ($topItems as $item) {
            $itemData[] = (int) $item->sold;
            $topItemLastMonthData[] = (int) ($lastMonthData[$item->item_id] ?? 0); // Use 0 if not found
            $topItemNames[] = $item->serviceRequest->name ?? 'Unknown';
        }

        // Final output array
        $data = [
            'this_month' => $itemData,
            'last_month' => $topItemLastMonthData,
            'name' => $topItemNames,
        ];

        return $data;
    }

    private function serviceRequestActivity($months, $monthNames, $startMonth, $endMonth)
    {
        // Fetch created and deleted counts per month
        $createdData = HospitalityService::selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        $deletedData = HospitalityService::onlyTrashed()
            ->selectRaw("
            DATE_FORMAT(deleted_at, '%Y-%m') as month,
            COUNT(id) as total_count
            ")
            ->whereBetween('deleted_at', [$startMonth, $endMonth])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('total_count', 'month');

        // Prepare final dataset
        $createdCounts = $months->map(fn ($m) => $createdData[$m] ?? 0)->toArray();
        $deletedCounts = $months->map(fn ($m) => $deletedData[$m] ?? 0)->toArray();

        $data = [
            'data' => [
                ['name' => 'Created', 'data' => $createdCounts],
                ['name' => 'Deleted', 'data' => $deletedCounts],
            ],
            'months' => $monthNames
        ];

        return $data;
    }

    private function enabledDisabledServiceRequestCount()
    {
        $enabledChannel = HospitalityService::where('is_enable', 1)->count();
        $disabledChannel = HospitalityService::where('is_enable', 0)->count();

        $data = [
            'enabled' => $enabledChannel,
            'disabled' => $disabledChannel
        ];

        return $data;
    }

    private function createdDeletedServiceRequestCount($startMonth, $endMonth)
    {
        $createdCount = HospitalityService::whereNull('deleted_at')->whereBetween('created_at', [$startMonth, $endMonth])->count();
        $deletedCount = HospitalityService::onlyTrashed()->whereBetween('created_at', [$startMonth, $endMonth])->count();

        $data = [
            'created' => $createdCount,
            'deleted' => $deletedCount
        ];

        return $data;
    }
}