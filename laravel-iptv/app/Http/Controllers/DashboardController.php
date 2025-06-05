<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
        // $this->middleware('permission:dashboard.index');
    }

    public function index(Request $request)
    {   
        $user = Auth::user();
        
        return view('/admin/dashboard/admin', compact('user'));
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
