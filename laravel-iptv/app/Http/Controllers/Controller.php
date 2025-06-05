<?php

namespace App\Http\Controllers;

use App\Models\Analytics\Analytic;
use App\Models\SystemSettings\DeviceAdb\AdbSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function stbMultiCurlIpList(array $ip, array $command): array 
    {
        $data = ['urls' => [], 'request_contents' => []];
        
        foreach ($ip as $address) {
            $data['urls'][] = $address . ":5000";

            // Fetch the device using the DB facade
            $result = DB::table('devices')->where('ip4_address', $address)->first();

            if ($result) {
                $data['request_contents'][] = [
                    'api_key' => $result->api_id,
                    'command' => $command
                ];
            }
        }

        // file_put_contents('debug/a1AAA1.txt', print_r($data, true)); // Debugging purpose
        return $data;
    }

    public function stbMultiCurlPost(array $request_contents, array $urls): array 
    {
        // Array of cURL handles
        $chs = [];
        // Create the array of cURL handles and add to a multi_curl
        $mh = curl_multi_init();
        $results = [];  // Store the results (success or error)

        foreach ($urls as $key => $url) {
            $chs[$key] = curl_init($url);
            curl_setopt($chs[$key], CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($chs[$key], CURLOPT_TIMEOUT, 10);
            curl_setopt($chs[$key], CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($chs[$key], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chs[$key], CURLOPT_POST, true);
            curl_setopt($chs[$key], CURLOPT_POSTFIELDS, json_encode($request_contents[$key]));

            curl_multi_add_handle($mh, $chs[$key]);
        }

        // Running the requests
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh); // Adds a short pause to avoid high CPU usage
        } while ($running);

        // Close the handles
        foreach ($chs as $key => $ch) {
            $response = curl_multi_getcontent($ch); // Get the response content
            $error = curl_error($ch);  // Check for any errors
            
            // Store success or error message in results array
            if ($error) {
                $results[$key] = [
                    'success' => false,
                    'error' => $error
                ];
            } else {
                $results[$key] = [
                    'success' => true,
                    'response' => $response
                ];
            }

            // Close individual handles
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        // Close multi-curl handle
        curl_multi_close($mh);

        // Return the results (including both responses and errors)
        return $results;
    }

    public function storeAnalytics(array $data)
    {
        try {
            // Create a new instance of Analytic
            $analytic = new Analytic();
    
            // Set the fields
            $analytic->counter = $data['counter'];
            $analytic->category_key = $data['category_key'];
            $analytic->item_id = $data['item_id'] ?? null;
            // If 'type_id' is not set, make it null
            $analytic->type_id = $data['type_id'] ?? null;
    
            // Save the data and return true if successful
            if ($analytic->save()) {
                return true;
            }
    
            // Return false if the save operation fails
            return false;
    
        } catch (\Exception $e) {
            // Handle any exceptions (e.g. logging the error, returning false, etc.)
            return false;
        }
    }

    public function pingDeviceAnalytics()
    {   

        $data = AdbSetting::find(1);

        if (!$data) {
            return response()->json([
                'status' => 'warning',
                'message' => 'ADB Settings not found.'
            ], 404);
        }

        $serverInterface = $data->server_interface;//'enp0s3';
        $scriptPath = base_path('/public/bash-scripts/adb_analytics_ping_device.sh');
        $package = $data->launcher_package_name;//"com.ph.bittelasia.meshtv.tv.holidayinn";
        $serverIp = $data->server_ip_address;//'192.168.110.29';
        $logPath = env('VAR_PATH')."/storage/logs/adb_analytics_device_list.log";

        // Fetch database credentials from .env
        $db_host = env('DB_HOST');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');

        $command = "nohup bash " . escapeshellarg($scriptPath) . "  " . escapeshellarg($serverInterface) . " " . escapeshellarg($package) . " " . escapeshellarg($serverIp) . " " . escapeshellarg($logPath) . " " . escapeshellarg($db_host) . " " . escapeshellarg($db_user) . " " . escapeshellarg($db_pass) . " " . escapeshellarg($db_name) ." > /dev/null 2>&1 &";
    
        // Execute the command
        shell_exec($command);

        // Immediately return a JSON response without waiting for the process to finish
        return response()->json([
            'message' => 'Process executed in the background.'
        ]);
    }

    public function checkStorePermission($id)
    {
        // if(auth()->user()->hasPermissionTo('menu_store.'.$id) || auth()->user()->hasRole('super-admin')) {
        if(auth()->user()->hasRole('super-admin')) {
        
            if(!empty($permissions)) {
                $this->checkHasAnyPermission($permissions);
            }
        } else {
            throw new Exception("403");
        }
    }

    public function checkHasAnyPermission($permissions = [])
    {
        if(!empty($permissions)) {
            if(auth()->user()->canany($permissions)) {

            } else {
                throw new \Exception("403");
            }
        }
    }


    protected function getWeeksStartAndEndDatesUTC($year, $month) {
        $startDate = new DateTime("$year-$month-01", new DateTimeZone('UTC'));
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');
    
        $weeks = [];
        $weekStart = clone $startDate;

        $monthWeek = 1;
    
        while ($weekStart <= $endDate) {
            $weekEnd = clone $weekStart;
            $weekEnd->modify('next Sunday');
            
            if ($weekEnd > $endDate) {
                $weekEnd = clone $endDate;
            }

            $N = $weekStart->format('N');

            if($N > 0 && $N < 6) {
                $sDate = (int) $weekStart->format('d');
                $eDate = (int) $weekEnd->format('d');
                $title = 'W'.$monthWeek.' ('.$weekStart->format('F').' '.$sDate.'-'.$eDate.')';
                $weeks[] = [
                    'start' => $weekStart->format('Y-m-d'),
                    'end' => $weekEnd->format('Y-m-d'),
                    'monthWeek' => $monthWeek,
                    'startDate' => $sDate,
                    'endDate' => $eDate,
                    'title' => $title
                ];
                $monthWeek += 1;
            }
            
            // Move to the start of the next week
            $weekStart->modify('next Monday');
        }
    
        return $weeks;
    }

    protected function isValidTimeString($timeString) {
        // Use DateTime::createFromFormat to try to parse the time string
        $dateTime = DateTime::createFromFormat('g:i A', $timeString);
        
        // Check if the parsing succeeded and if the input matches the format exactly
        return $dateTime && $dateTime->format('g:i A') === $timeString;
    }
    
    protected function convertTo24HourFormat($timeString) {
        if (!$this->isValidTimeString($timeString)) {
            return false;
        }
    
        $dateTime = DateTime::createFromFormat('g:i A', $timeString);
        return $dateTime->format('H:i');
    }


}
