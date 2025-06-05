<?php

namespace App\Repositories\General\Body\Devices;

use App\Events\General\Body\Devices\DevicesUpdated;
use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Devices\IDeviceRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class DeviceRepository extends Controller implements IDeviceRepository 
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'device';
    }

    public function get_data($request)
    {
        
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = Device::with('room', 'category', 'language');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'room') {
                        $query->orWhere("devices.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }  
            }   
            $query->orWhereHas('room', function ($query) use ($search) {
                    $query->where("rooms.name", "like", '%' . $search . '%');
                });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        if ($orderByCol === 'room') {
            // If ordering by room name, join with the rooms table
            $query = $query->join('rooms', 'devices.room_id', '=', 'rooms.id')
                        ->orderBy('rooms.name', $orderBy);
        } else {
            // Otherwise, order by columns in the devices table
            $query = $query->orderBy("devices.$orderByCol", $orderBy); // Prefix with table name
        }
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            switch($value->current_status){
                case 'Active':
                    $color = 'success';
                break;

                case 'Inactive':
                    $color = 'danger';
                break;

                case 'Standby':
                    $color = 'warning';
                break;

                default:
                    $color = 'secondary';
            }
            // $actions = '<div class="d-flex order-actions">';
            // $actions .= '<button class="btn btn-sm btn-outline-danger me-1"
            //         title="Generate PDF"
            //         onclick="generatePDF('.$value->id.');"><i class="fa fa-file-pdf"></i>
            //     </button>';
            // $actions .= '<button class="btn btn-sm btn-primary print-btn me-1"
            //     title="Download"
            //     onclick="downloadPDF('.$value->id.');"><i class="fa fa-download"></i>
            // </button>';
            // if(auth()->user()->can('menu_store.eod_register_report.deposit.update')) {
            //     $actions .= '
            //         <button class="btn btn-sm btn-primary me-1" 
            //             id="data-show-btn-'.$value->id.'"
            //             data-id="'.$value->id.'" 
            //             data-array="'.htmlspecialchars(json_encode($value)).'"
            //             onclick="showEditForm('.$value->id.');"><i class="fa fa-pencil"></i>
            //         </button>';
            // }
            // if(auth()->user()->can('menu_store.eod_register_report.deposit.delete')) {
            //     $actions .= '<button class="btn btn-sm btn-danger" onclick="ShowConfirmDeleteForm(' . $value->id . ')"><i class="fa fa-trash-can"></i></button>';
            // }             
            // $actions .= '</div>';
            if(auth()->user()->canany(['devices.view','devices.update', 'devices.delete', 'devices.adb'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                    <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('devices.adb')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"><i class="fa-brands fa-android text-primary"></i> Reset Data</a></li>';
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" 
                        onclick=\'adbReboot("'.$value->ip4_address.'");\'><i class="fa-brands fa-android text-primary"></i> Reboot</a></li>';
                }
                if(auth()->user()->can('devices.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('devices.update')) {
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)"  class="me-1"
                                    onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('devices.delete')) {
                    $actions .= '<li>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="ShowConfirmDeleteForm('.$value->id.')">
                                        <i class="fa fa-trash text-danger"></i> Delete
                                    </a>
                                </li>';
                }
                $actions .= '</ul>
                    </div>';
            }
            else{
                $actions = '';
            }
            
            $newData[] = [
                'id' => $value->id,
                'mac_address' => $value->mac_address,
                'ip4_address' => $value->ip4_address,
                'room' => $value->room->name,
                'os_version' => $value->os_version,
                'current_status' => '<span class="text-'.$color.'"><i class="fa fa-circle"></i> '.$value->current_status.'</span> ',
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function detectDevices()
    {   
        $devices = Device::all();
        $ip = [];
        
        // Collect unique IP addresses
        foreach ($devices as $device) {
            $device->current_status = 'Inactive';
            $device->save();
            if (!in_array($device->ip4_address, $ip)) {
                $ip[] = $device->ip4_address; // Use shorthand to add elements to the array
            }
        }

        // Now fire the event for all updated devices (use for live changes datatable)
        event(new DevicesUpdated($devices)); // Pass the collection of updated devices

        
        $commands = [
            'check_active_stb',
            // 'get_all_room',
        ];

        $conditions = [
            //empty conditions to get all
        ];
        
        // Queue the job to be executed after commit
        DB::afterCommit(function () use ($commands, $conditions) {
            // Dispatch the job in the background
            SendDataToDevicesJob::dispatch($commands, $conditions);
        });

        DB::commit();

        // Start the queue worker temporarily to process the job
        shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

        // Sleep for a second (use cautiously)
        sleep(1);

        return;
    }

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();

            // Retrieve the existing record
            $data = Device::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();
            
            $data->category_id = ($post['category_id'] == 0)?null:$post['category_id'];
            $data->language_id = ($post['language_id'] == 0)?null:$post['language_id'];

            
            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
               
                $commands = [
                    'get_iptv_ui',
                    'get_theme',
                    'get_language'
                ];
        
                $conditions = [
                    ['field' => 'ip4_address', 'operator' => '=', 'value' => ''.$data->ip4_address.''],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);
                });

                DB::commit();

                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been updated.'
                ], 200);
            } else {
                // Failed to save the record
                DB::rollBack();
                
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not updated.'
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

    public function delete($request)
    {   
        try {
            DB::beginTransaction();
            $input = $request->all();

            $id = $input['id'];
            
            $data = Device::where('id', $id)->first();
            

            if ($data) {
                $data->delete();

                $this->logHistoriesRepo->delete($id, $this->activityName);
                
                DB::commit();
                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been deleted.'
                ], 200);
            } else {
                // Failed to save the record
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not deleted.'
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

    public function adbReboot($request)
    {
        $ip = request()->ip();//get_ip of users
        $ip = $request->ip;
        
        if($ip){
            $result = shell_exec(base_path()."/public/bash-scripts/adb_reboot_stb.sh '".$ip."'");
            
            $lines = explode("\n", trim($result));
            
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed'
            ];
            

            // sleep(1);
            return json_encode($result);

            //bash debugging
            $scriptPath = base_path('public/bash-scripts/adb_reboot_stb.sh');
            $result = shell_exec("{$scriptPath} '{$ip}'");
            $process = new Process([$scriptPath]);
            $process->run();

            // Check if the process was successful
            if ($process->isSuccessful()) {
                // Return the output
                return response()->json(['output' => $process->getOutput()]);
            }

            // Capture and return the error output if something went wrong
            return response()->json([
                'error' => $process->getErrorOutput(),
                'exit_code' => $process->getExitCode(),
            ], 500);
        }

        return $ip;
    }

    public function adbResetData($request)
    {
        $ip = request()->ip();//get_ip of users
        $ip = $request->ip;
        
        if($ip){
            $result = shell_exec(base_path()."/public/bash-scripts/adb_reset_data.sh '".$ip."'");
            
            $lines = explode("\n", trim($result));
            
            // Prepare the JSON-like response
            $response = [
                'result' => $lines[0] ?? 'Failed'
            ];
            

            // sleep(1);
            return json_encode($result);
        }
        
        return $ip;
    }
}