<?php

namespace App\Repositories\General\Body\Devices;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Devices\IDeviceCategoryRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Devices\DeviceCategory;
use Illuminate\Support\Facades\DB;

class DeviceCategoryRepository extends Controller implements IDeviceCategoryRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'device group';
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
        $query = new DeviceCategory;

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'room') {
                        $query->orWhere("device_categories.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }  
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        
        $query = $query->orderBy("device_categories.$orderByCol", $orderBy);

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            if(auth()->user()->canany(['device_group.view','device_group.update', 'device_group.delete', 'device_group.adb'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('device_group.change_order')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" class="me-1"
                        onclick="showChangeOrderModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-arrows-v text-primary me-1 ms-1"></i> Change Order</a></li>';
                }
                if(auth()->user()->can('device_group.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('device_group.edit')) {
                    // $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="showEditModal()"><i class="fa fa-edit"></i> Edit</a></li>';
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info me-1"></i> Edit</a></li>';
                }
                if(auth()->user()->can('device_group.delete')) {
                    $actions .= '<li>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="ShowConfirmDeleteForm('.$value->id.')">
                                        <i class="fa fa-trash text-danger me-1"></i> Delete
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
                'name' => $value->name,
                'description' => $value->description,
                'order_no' => $value->order_no,
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function store($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            $data = new DeviceCategory();
            $data->name = $post['name'];
            $data->description = $post['description'];
            $data->order_no = $post['order_no'];

            $data->save();

            // Convert the role to an array
            $dataArray = $data->toArray();

            // Add the permissions to the array
            unset($dataArray['updated_at']);
            unset($dataArray['created_at']);

            $this->logHistoriesRepo->store($dataArray, $this->activityName);

            DB::commit();

            if ($data->save()) {
                // Record saved successfully
                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been saved.'
                ], 201);
            } else {
                // Failed to save the record
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

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $data = DeviceCategory::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->name = $post['name'];
            $data->description = $post['description'];
            $data->order_no = $post['order_no'];

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                $commands = [
                    'get_iptv_ui',
                    'get_theme',
                ];
        
                $conditions = [
                    ['field' => 'category_id', 'operator' => '=', 'value' => ''.$data->id.''],
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
            
            $data = DeviceCategory::where('id', $id)->first();
            

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

    public function changeOrder($request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $data = DeviceCategory::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->order_no = $post['order_no'];


            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
                DB::commit();

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
}