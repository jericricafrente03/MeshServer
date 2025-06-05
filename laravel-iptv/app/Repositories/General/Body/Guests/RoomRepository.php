<?php

namespace App\Repositories\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Guests\IRoomRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RoomRepository extends Controller implements IRoomRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'room';
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
        $query = Room::with('category', 'devices', 'status');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'category' && $column['name'] !== 'status') {
                        $query->orWhere("rooms.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }  
            }
            $query->orWhereHas('category', function ($query) use ($search) {
                $query->where("room_categories.name", "like", '%' . $search . '%');
            });
            $query->orWhereHas('status', function ($query) use ($search) {
                $query->where("general_statuses.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'category') {
            $query->join('room_categories', 'rooms.category_id', '=', 'room_categories.id')
                ->select('rooms.*', 'room_categories.name as category_name')
                ->distinct()
                ->orderBy('room_categories.name', $orderBy);
        } elseif ($orderByCol === 'status') {
            $query->join('general_statuses', 'rooms.room_status', '=', 'general_statuses.id')
                ->select('rooms.*', 'general_statuses.name as status_name')
                ->distinct()
                ->orderBy('general_statuses.name', $orderBy);
        } else {
            $query->orderBy("rooms.$orderByCol", $orderBy);
        }

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            $mac_address = '';
            
            foreach($value->devices as $row){
                // $mac_address .= $row->mac_address.', ';
                $random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                $mac_address .= '<a style="min-width: 150px; font-weight:bold; cursor: default; background-color: '.$random_color.'; color: white;" type="button" class="btn btn-sm px-3">
                    <small>'.$row->mac_address.'</small>
                </a> ';
            }
            
            if(auth()->user()->canany(['room.view','room.update', 'room.delete', 'room.adb'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('room.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('room.edit')) {
                    // $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="showEditModal()"><i class="fa fa-edit"></i> Edit</a></li>';
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('room.delete')) {
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
                'name' => $value->name,
                'status' => '<a style="background-color: '.$value->status->bg_color.'; color: '.$value->status->color.'; min-width: 150px; font-weight:bold; cursor: default;" type="button" class="btn btn-default btn-sm px-3 d-flex justify-content-start align-items-center">
                        <i class="'.$value->status->icon.' fa-xl me-2"></i> <small>'.ucfirst($value->status->name).'</small>
                    </a>',
                'category' => $value->category->name,
                'mac_address' => $mac_address,
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
            
            $data = new Room();
            $data->name = $post['name'];
            $data->room_status = $post['room_status'];
            $data->category_id = $post['category_id'];

            $data->save();

            // Convert the role to an array
            $dataArray = $data->toArray();

            // Remove to the array
            unset($dataArray['updated_at']);
            unset($dataArray['created_at']);

            $this->logHistoriesRepo->store($dataArray, $this->activityName);

            // Queue the job to be executed after commit
            DB::afterCommit(function () {
                // Run the artisan command for updating devices
                Artisan::call('devices:updated', ['type' => 'room']);
            });

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
            $data = Room::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Room not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();
            
            $data->name = $post['name'];
            $data->category_id = $post['category_id'];
            $data->room_status = $post['room_status'];

            

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Check for room_assignments with customer_id = $data->id and check_is = 0
                $roomAssignments = RoomAssignment::where('room_id', $data->id)
                    ->where('is_checkout', 0)
                    ->get();

                foreach ($roomAssignments as $assignment) {
                    $assignment->room_number = $data->name;
                    $assignment->save();
                }
                
                $commands = [
                    'get_rooms',
                ];
        
                $conditions = [
                    ['field' => 'room_id', 'operator' => '=', 'value' => ''.$data->id.''],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);

                    // Run the artisan command for updating devices
                    Artisan::call('devices:updated', ['type' => 'room']);
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

            // Check if the guest is currently checked in
            $isCheckedIn = RoomAssignment::where('room_id', $id)
                ->where('is_checkout', 0)
                ->exists(); // Use exists() for a boolean result
        
            if ($isCheckedIn) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Cannot delete the room. The guest is currently checked in.'
                ], 400); // Use 400 Bad Request for this scenario
            }
            
            $data = Room::where('id', $id)->first();
            

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

}