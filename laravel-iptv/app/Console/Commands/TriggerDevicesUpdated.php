<?php

namespace App\Console\Commands;

use App\Events\General\Body\Devices\DevicesUpdated;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use App\Models\GeneralStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TriggerDevicesUpdated extends Command
{
    protected $signature = 'devices:updated {type}';
    protected $description = 'Trigger the DevicesUpdated event based on type';

    public function handle()
    {
        // Get the passed type argument
        $type = $this->argument('type');

        switch ($type) {
            case 'stb':
                $activeCount = Device::where('current_status', 'Active')->count();
                $inactiveCount = Device::where('current_status', 'Inactive')->count();

                $result = [
                    'type' => 'stb',
                    'active' => $activeCount,
                    'inactive' => $inactiveCount
                ];
            break;
            case 'guest':
                $checkinCount = RoomAssignment::where('is_checkout', 0)->count();
                $checkoutCount = RoomAssignment::where('is_checkout', 1)
                    ->whereDate('check_out', Carbon::today()) // Check-in date is today
                    ->count();

                $result = [
                    'type' => 'guest',
                    'checkin' => $checkinCount,
                    'checkout' => $checkoutCount
                ];
            break;
            case 'room':
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
        
                $result = [
                    'type' => 'room',
                    'data' => $formattedData,
                    'color' => $colors
                ];
            break;
            case 'system_info':
                $cpuLoad = sys_getloadavg();

                // Memory Usage (Real Memory)
                $memoryInfo = $this->getMemoryUsage();

                // Virtual Memory Usage
                $virtualMemoryInfo = $this->getVirtualMemoryUsage();

                // Disk Usage
                $diskTotal = disk_total_space('/');
                $diskFree = disk_free_space('/');
                $diskUsed = $diskTotal - $diskFree;

                $stats = [
                    'cpu' => $this->formatPercentage($cpuLoad[0]),
                    'memory_used' => $memoryInfo['used'],
                    'memory_free' => $memoryInfo['free'],
                    'memory_total' => $memoryInfo['total'],
                    'memory_used_percent' => $this->formatPercentage($this->calculatePercentage($memoryInfo['used'], $memoryInfo['total'])),
                    'memory_free_percent' => $this->formatPercentage($this->calculatePercentage($memoryInfo['free'], $memoryInfo['total'])),
                    'virtual_memory_used' => $virtualMemoryInfo['used'],
                    'virtual_memory_free' => $virtualMemoryInfo['free'],
                    'virtual_memory_total' => $virtualMemoryInfo['total'],
                    'virtual_memory_used_percent' => $this->formatPercentage($this->calculatePercentage($virtualMemoryInfo['used'], $virtualMemoryInfo['total'])),
                    'virtual_memory_free_percent' => $this->formatPercentage($this->calculatePercentage($virtualMemoryInfo['free'], $virtualMemoryInfo['total'])),
                    'disk_used' => $this->formatBytes($diskUsed),
                    'disk_free' => $this->formatBytes($diskFree),
                    'disk_used_percent' => $this->formatPercentage($this->calculatePercentage($diskUsed, $diskTotal)),
                    'disk_free_percent' => $this->formatPercentage($this->calculatePercentage($diskFree, $diskTotal)),
                ];

                $result = [
                    'type' => 'system_info',
                    'stats' => $stats,
                ];
            break;
            default:
                $this->error("Invalid type. Use 'stb' or 'guest'.");
                $result = [
                    'type' => 'failed',
                ];
                return;
            break;
        }

        broadcast(new DevicesUpdated($result));
        // Output the result for logs
        $this->info('DevicesUpdated event triggered with result: ' . json_encode($result));
    }

    private function getMemoryUsage()
    {
        $data = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+) kB/', $data, $totalMatches);
        preg_match('/MemFree:\s+(\d+) kB/', $data, $freeMatches);

        $total = $totalMatches[1] ?? 0;
        $free = $freeMatches[1] ?? 0;
        $used = $total - $free;

        return [
            'total' => round($total / 1024), // Convert kB to MB
            'free' => round($free / 1024),
            'used' => round($used / 1024),
        ];
    }

    private function getVirtualMemoryUsage()
    {
        $data = file_get_contents('/proc/meminfo');
        preg_match('/SwapTotal:\s+(\d+) kB/', $data, $totalMatches);
        preg_match('/SwapFree:\s+(\d+) kB/', $data, $freeMatches);

        $total = $totalMatches[1] ?? 0;
        $free = $freeMatches[1] ?? 0;
        $used = $total - $free;

        return [
            'total' => round($total / 1024), // Convert kB to MB
            'free' => round($free / 1024),
            'used' => round($used / 1024),
        ];
    }

    private function calculatePercentage($part, $total)
    {
        return $total > 0 ? ($part / $total) * 100 : 0;
    }

    private function formatPercentage($value)
    {
        return number_format($value, 2); // Format to two decimal places
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
