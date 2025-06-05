<?php

namespace App\Repositories\SystemSettings\DeviceAdb;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\DeviceAdb\IManagerRepository;
use App\Models\General\Body\Devices\Device;
use App\Models\SystemSettings\DeviceAdb\AdbDevice;
use App\Models\SystemSettings\DeviceAdb\AdbSetting;
use Illuminate\Support\Facades\DB;
use PHPUnit\Event\Code\Test;
use Symfony\Component\Process\Process;

class ManagerRepository extends Controller implements IManagerRepository
{
    protected $logHistoriesRepo;
    protected $activityName;
    protected $varPath;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'device adb manager';
        $this->varPath = env('VAR_PATH');
    }

    public function getDevices()
    {   

        $data = AdbSetting::find(1);

        if (!$data) {
            return response()->json([
                'status' => 'warning',
                'message' => 'ADB Settings not found.'
            ], 404);
        }
        
        $serverInterface = $data->server_interface;//'enp0s3';
        $scriptPath = base_path('/public/bash-scripts/adb_device_list.sh');
        $scriptPath2 = base_path('/public/bash-scripts/adb_device_status.sh');
        $package = $data->launcher_package_name;//"com.ph.bittelasia.meshtv.tv.holidayinn";
        $serverIp = $data->server_ip_address;//'192.168.110.29';
        $logPath = $this->varPath."/storage/logs/adb_device_list.log";
        
        // Fetch database credentials from .env
        $db_host = env('DB_HOST');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');
        // $allowedModels = explode(',', env('ALLOWED_MODELS', ''));
        // $allowedModels = env('ALLOWED_MODELS', '');
        // $allowedModelsString = implode(',', $allowedModels); // Ensure it's a string
        $allowedModels = explode(',', env('ALLOWED_MODELS', '')); // Convert string to array
        $allowedModelsString = implode(',', $allowedModels); // Ensure it's a string
       
        // Update the status of all adb_device items to 'inactive'
        AdbDevice::query()->update(['status' => 'inactive']);
       
        // $command = "nohup bash $scriptPath $serverInterface $scriptPath2 $package $serverIp $logPath \"$db_host\" \"$db_user\" \"$db_pass\" \"$db_name\" \"$allowedModelsString\" > /dev/null 2>&1 &";
        $command = "nohup bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($serverInterface) . " " . escapeshellarg($scriptPath2) . " " . escapeshellarg($package) . " " . escapeshellarg($serverIp) . " " . escapeshellarg($logPath) . " " . escapeshellarg($db_host) . " " . escapeshellarg($db_user) . " " . escapeshellarg($db_pass) . " " . escapeshellarg($db_name) . " " . escapeshellarg($allowedModelsString) . " > /dev/null 2>&1 &";
        // Execute the command
        shell_exec($command);

        // Immediately return a JSON response without waiting for the process to finish
        return response()->json([
            'message' => 'Process executed in the background.'
        ]);
    }

    public function pingDevices()
    {   

        $data = AdbSetting::find(1);

        if (!$data) {
            return response()->json([
                'status' => 'warning',
                'message' => 'ADB Settings not found.'
            ], 404);
        }

        $serverInterface = $data->server_interface;//'enp0s3';
        $scriptPath = base_path('/public/bash-scripts/adb_ping_device.sh');
        $package = $data->launcher_package_name;//"com.ph.bittelasia.meshtv.tv.holidayinn";
        $serverIp = $data->server_ip_address;//'192.168.110.29';
        $logPath = $this->varPath."/storage/logs/adb_device_list.log";

        // Fetch database credentials from .env
        $db_host = env('DB_HOST');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');

        $command = "nohup bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($serverInterface) . " " . escapeshellarg($package) . " " . escapeshellarg($serverIp) . " " . escapeshellarg($logPath) . " " . escapeshellarg($db_host) . " " . escapeshellarg($db_user) . " " . escapeshellarg($db_pass) . " " . escapeshellarg($db_name) . " > /dev/null 2>&1 &";
    
        // Execute the command
        shell_exec($command);

        // Immediately return a JSON response without waiting for the process to finish
        return response()->json([
            'message' => 'Process executed in the background.'
        ]);
    }

    public function getData($request)
    {
        if ($request->refresh != 'yes') {
            // Do not call $this->getDevices() if refresh is not requested
            $this->getDevices(); // Only execute if it's a manual request
        }
        else{
            $this->pingDevices();
        }

        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = AdbDevice::query();

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("adb_devices.{$column['name']}", 'like', '%' . $search . '%');  
                }  
            }   
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
       
        // Otherwise, order by columns in the devices table
        $query = $query->orderBy("adb_devices.$orderByCol", $orderBy); // Prefix with table name
    
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            switch($value->status){
                case 'active':
                    $color = 'success';
                    $disabled = '';
                break;

                case 'inactive':
                    $color = 'danger';
                    $disabled = 'disabled';
                break;

                case 'Standby':
                    $color = 'warning';
                break;

                default:
                    $color = 'secondary';
                    $disabled = 'disabled';
            }

            if(auth()->user()->canany(['device_adb_manager.screencapture', 'device_adb_manager.screenrecord'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('device_adb_manager.screencapture')) {
                    $actions .= '<button title="Screen Capture" class="btn btn-success btn-sm me-2 fixed-size-btn '.$disabled.'" 
                            onclick="screenCaptureRecord('.$value->id.', \''.$value->ip4_address.'\', \'capture\', \''.$value->mac_address.'\')">
                            <i class="fa-solid fa-camera"></i>
                        </button>';
                }

                if(auth()->user()->can('device_adb_manager.screenrecord')) {
                    $actions .= '<button title="Screen Record" class="btn btn-danger btn-sm me-2 fixed-size-btn '.$disabled.'" 
                            onclick="screenCaptureRecord('.$value->id.', \''.$value->ip4_address.'\', \'record\', \''.$value->mac_address.'\')">
                            <i class="fa-solid fa-video"></i>
                        </button>';
                }

                $actions .= '</div>';
            }
            else{
                $actions = '';
            }
            
            $newData[] = [
                'id' => $value->id,
                'mac_address' => $value->mac_address,
                'ip4_address' => $value->ip4_address,
                'foreground' => $value->foreground,
                'launcher_version' => $value->launcher_version,
                'model' => $value->model,
                'architecture' => $value->architecture,
                // 'room' => $value->room->name,
                'launcher' => $value->launcher,
                'status' => '<span class="text-'.$color.'"><i class="fa fa-circle"></i> '.$value->status.'</span> ',
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function getApkList($request){
        $dir = getcwd().'/storage/upload/apk/';
     	$scan_result = preg_grep('~\.(apk)$~', scandir($dir,0));
        foreach ($scan_result as $key => $value) {
            $data[] = $value;
        }
        
        return @$data;
    }

    public function groupInstall($request)
    {   
        $type = 'install';
        ini_set('max_execution_time', 300); // 5 minutes
        $ipStart = $request->ip_start;
        $ipEnd = $request->ip_end;
        $apk = $request->apk;
        $scriptPath = base_path('/public/bash-scripts/adb_group_install.sh');
    
        //Debug IP Conversion
        \Log::info("Start IP: $ipStart (" . ip2long($ipStart) . ")");
        \Log::info("End IP: $ipEnd (" . ip2long($ipEnd) . ")");
    
        //Check for Single IP Case
        if (ip2long($ipStart) === ip2long($ipEnd) || empty($ipEnd)) {
            \Log::info("Detected as single IP! Skipping loop.");
            $response = $this->runAdbScript($scriptPath, $ipStart, $apk, $type);
            return response()->json([
                'output' => $response['output'],
                'message' => $response['message'],
                'status' => $response['status']
            ], 201);
        }

        //Validate IP Format
        if (!filter_var($ipStart, FILTER_VALIDATE_IP) || !filter_var($ipEnd, FILTER_VALIDATE_IP)) {
            return response()->json(['error' => 'Invalid IP format'], 400);
        }

        $failedIps = [];
        $allResults = [];

        //Convert IPs to long integer for proper looping
        $startLong = ip2long($ipStart);
        $endLong = ip2long($ipEnd);

        //Loop through IP range
        for ($currentLong = $startLong; $currentLong <= $endLong; $currentLong++) {
            $currentIp = long2ip($currentLong);
            \Log::info('IP: ' . $currentIp);
            
            $result = $this->runAdbScript($scriptPath, $currentIp, $apk, $type);
            $allResults[] = $result;

            if ($result['status'] === 'warning') {
                $failedIps[] = $currentIp;
            }

            sleep(1); //Prevents flooding the network
        }

        $status = empty($failedIps) ? 'success' : 'warning';
        $message = empty($failedIps) ? "All installations successful" : "Failed IPs: " . implode(', ', $failedIps);

        return response()->json([
            'status' => $status,
            'message' => $message,
            'results' => $allResults
        ], 201);

    }

    public function groupUninstall($request)
    {   
        $type = 'uninstall';
        ini_set('max_execution_time', 300); // 5 minutes
        $ipStart = $request->ip_start;
        $ipEnd = $request->ip_end;
        $packageName = $request->package_name;
        $scriptPath = base_path('/public/bash-scripts/adb_group_uninstall.sh');
    
        //Check for Single IP Case
        if (ip2long($ipStart) === ip2long($ipEnd) || empty($ipEnd)) {
            $response = $this->runAdbScript($scriptPath, $ipStart, $packageName, $type);
            return response()->json([
                'output' => $response['output'],
                'message' => $response['message'],
                'status' => $response['status']
            ], 201);
        }

        $failedIps = [];
        $allResults = [];

        //Convert IPs to long integer for proper looping
        $startLong = ip2long($ipStart);
        $endLong = ip2long($ipEnd);

        //Loop through IP range
        for ($currentLong = $startLong; $currentLong <= $endLong; $currentLong++) {
            $currentIp = long2ip($currentLong);
            
            $result = $this->runAdbScript($scriptPath, $currentIp, $packageName, $type);
            $allResults[] = $result;

            if ($result['status'] === 'warning') {
                $failedIps[] = $currentIp;
            }

            sleep(1); //Prevents flooding the network
        }

        $status = empty($failedIps) ? 'success' : 'warning';
        $message = empty($failedIps) ? "All uninstallations successful" : "Failed IPs: " . implode(', ', $failedIps);

        return response()->json([
            'status' => $status,
            'message' => $message,
            'results' => $allResults
        ], 201);

    }

    public function settings($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            $oldData = AdbSetting::find(1);

            if (!$oldData) {
                $oldDataArray = [];
            }
            else{
                $oldDataArray = $oldData->toArray();
            }

            AdbSetting::truncate();
    
            $data = new AdbSetting();
            $data->server_interface = $post['server_interface'];
            $data->launcher_package_name = $post['package_name'];
            $data->server_ip_address = $post['server_ip_address'];
            

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $newDataArray = $dataArray;
                $activity = 'Adb Settings = '.$data->id.'';
                $this->logHistoriesRepo->customUpdate($oldDataArray, $newDataArray, $activity);


                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been saved.'
                ], 201);
            } else {
                // Failed to save the record

                DB::rollBack(); 
                
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not saved.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function screenCapture($request)
    {   
        $ipAddress = $request->ip_address;
        $macAddress = $request->mac_address;
        $scriptPath = base_path('/public/bash-scripts/adb_screen_capture.sh');
        $storePath = base_path('/storage/app/public/upload/device_adb/screen_capture/');
        $logPath = $this->varPath."/storage/logs/adb_device_screen_capture.log";

        // Fetch database credentials from .env
        $db_host = env('DB_HOST');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');

        $command = "nohup bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($ipAddress) . " " . escapeshellarg($macAddress) . " " . escapeshellarg($logPath) . " " . escapeshellarg($storePath) . " " . escapeshellarg($db_host) . " " . escapeshellarg($db_user) . " " . escapeshellarg($db_pass) . " " . escapeshellarg($db_name) . " > /dev/null 2>&1 &";
    
        // Execute the command
        shell_exec($command);

        // Immediately return a JSON response without waiting for the process to finish
        return response()->json([
            'status' => 'success',
            'message' => 'Process executed in the background.'
        ], 200);
    }

    public function screenRecord($request)
    {
        $ipAddress = $request->ip_address;
        $macAddress = $request->mac_address;
        $scriptPath = base_path('/public/bash-scripts/adb_screen_record.sh');
        $storePath = base_path('/storage/app/public/upload/device_adb/screen_record/');
        $logPath = $this->varPath."/storage/logs/adb_device_screen_record.log";

        // Fetch database credentials from .env
        $db_host = env('DB_HOST');
        $db_user = env('DB_USERNAME');
        $db_pass = env('DB_PASSWORD');
        $db_name = env('DB_DATABASE');

        $command = "nohup bash " . escapeshellarg($scriptPath) . " " . escapeshellarg($ipAddress) . " " . escapeshellarg($macAddress) . " " . escapeshellarg($logPath) . " " . escapeshellarg($storePath) . " " . escapeshellarg($db_host) . " " . escapeshellarg($db_user) . " " . escapeshellarg($db_pass) . " " . escapeshellarg($db_name) . " > /dev/null 2>&1 &";
    
        // Execute the command
        shell_exec($command);

        // Immediately return a JSON response without waiting for the process to finish
        return response()->json([
            'status' => 'success',
            'message' => 'Process executed in the background.'
        ], 200);
    }

    private function runAdbScript($scriptPath, $currentIp, $secondParameter, $type)
    {
        try {
            // Run the script with ADB command
            switch ($type) {
                case 'install':
                    $process = new Process([$scriptPath, $currentIp, $secondParameter]);
                    break;
                case 'uninstall':
                    $process = new Process([$scriptPath, $currentIp, $secondParameter]);
                    break;
                default:
                    # code...
                    break;
            }
            
            $process->setTimeout(5); // Prevent infinite hanging
            $process->run();

            // Capture output and errors
            $output = trim($process->getOutput());
            $error = trim($process->getErrorOutput());

            // Determine success or warning based on output
            $status = (strpos($output, 'Success') !== false) ? 'success' : 'warning';

            return [
                'ip' => $currentIp,
                'status' => $status,
                'message' => $output ?: $error,
                'output' => $output,
                'error' => $error
            ];
        } catch (\Throwable $e) {
            return [
                'ip' => $currentIp,
                'status' => 'warning',
                'message' => "Error: " . $e->getMessage(),
                'output' => '',
                'error' => $e->getMessage()
            ];
        }
    }


}