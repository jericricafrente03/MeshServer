<?php

namespace App\Repositories\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Guests\IRoomAssignmentRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\Guest;
use App\Models\General\Body\Guests\GuestBilling;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use App\Models\GeneralStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoomAssignmentRepository extends Controller implements IRoomAssignmentRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'room assignment';
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
        $query = RoomAssignment::with('guest', 'room')->where('is_checkout', 0);

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'guest' && $column['name'] !== 'room' && $column['name'] !== 'check_in' && $column['name'] !== 's_check_out') {
                        $query->orWhere("room_assignments.{$column['name']}", 'like', '%' . $search . '%');
                    }
                    // Search logic for formatted check_in
                    if($column['name'] == 'check_in'){
                        $query->orWhereRaw("DATE_FORMAT(room_assignments.check_in, '%Y-%m-%d %h:%i %p') LIKE ?", ['%' . $search . '%']);
                    }
                    if($column['name'] == 's_check_out'){
                        $query->orWhereRaw("DATE_FORMAT(room_assignments.s_check_out, '%Y-%m-%d %h:%i %p') LIKE ?", ['%' . $search . '%']);
                    }
                }  
            }

            $query->orWhereHas('guest', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereRaw("CONCAT(guests.title, ' ', guests.firstname, ' ', guests.lastname) LIKE ?", ['%' . $search . '%']);
                });
                // dd($query->toSql(), $query->getBindings()); // Check what the query looks like
            });
            $query->orWhereHas('room', function ($query) use ($search) {
                $query->where("rooms.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        
        // if ($orderByCol === 'room') {
        //     $query->join('rooms', 'room_assignments.room_id', '=', 'rooms.id')
        //         ->select('room_assignments.*', 'rooms.name as room_name')
        //         ->distinct()
        //         ->orderBy('room_categories.name', $orderBy);
        // } elseif ($orderByCol === 'guest') {
        //     $query->orderByRaw("CONCAT(guests.firstname, ' ', guests.lastname) $orderBy");
        // } else {
        //     $query->orderBy("room_assignments.$orderByCol", $orderBy);
        // }

        if ($orderByCol === 'room') {
            $query->join('rooms', 'room_assignments.room_id', '=', 'rooms.id')
                  ->select('room_assignments.*', 'rooms.name as room_name')
                  ->distinct()
                  ->orderBy('rooms.name', $orderBy);
        } 
        elseif ($orderByCol === 'guest') {
            $query->join('guests', 'room_assignments.customer_id', '=', 'guests.id') // Join guests table for ordering
                  ->select('room_assignments.*', 'guests.firstname', 'guests.lastname')
                  ->distinct()
                  ->orderByRaw("CONCAT(guests.firstname, ' ', guests.lastname) $orderBy"); // Order by guest name
        } 
        else {
            $query->orderBy("room_assignments.$orderByCol", $orderBy);
        }

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            $name = ($value->guest->title)?$value->guest->title .' '. $value->guest->firstname .' '. $value->guest->lastname:$value->guest->firstname .' '. $value->guest->lastname;
            
            $checkInBg = (date('a', strtotime($value->check_in)) == 'pm')?'#8833ff':'#0d6efd';
            $checkIn = date('Y-m-d h:i', strtotime($value->check_in)).' <a style="min-width: 5px; font-weight:bold; cursor: default; background-color: '.$checkInBg.'; color: white;" type="button" class="btn btn-sm">
                <small>'.strtoupper(date('a', strtotime($value->check_in))).'</small>
            </a>';
            $checkOutBg = (date('a', strtotime($value->s_check_out)) == 'pm')?'#8833ff':'#0d6efd';
            $checkOut = date('Y-m-d h:i', strtotime($value->s_check_out)).' <a style="min-width: 5px; font-weight:bold; cursor: default; background-color: '.$checkOutBg.'; color: white;" type="button" class="btn btn-sm">
                <small>'.strtoupper(date('a', strtotime($value->s_check_out))).'</small>
            </a>';

            $count = GuestBilling::where('room_assignment_id', $value->id)
                ->where(function($query) {
                    $query->where('is_paid', '!=', 1)
                        ->where('status_id', '!=', 15);
                })
                ->count();
            
            if(auth()->user()->canany(['room_assignments.billing', 'room_assignments.checkout', 'room_assignments.delete', 'room_assignments.changeroom',])) {
                $actions = '';
                if(auth()->user()->can('room_assignments.billing')) {
                    $actions .= '
                        <button title="Billing" class="btn btn-sm btn-primary" 
                            data-id="'.$value->id.'" 
                            onclick="showBillingModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                        <i class="fa fa-book"></i>
                        </button>';
                }
                if(auth()->user()->can('room_assignments.changeroom')) {
                    $actions .= '
                        <button title="Change Room" class="btn btn-sm btn-info"
                            data-id="'.$value->id.'" 
                            onclick="showChangeRoomForm('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                        <i class="fa-solid fa-person-booth"></i>
                        </button>';
                }

                if ($count === 0) {
                    if(auth()->user()->can('room_assignments.checkout')) {
                        $actions .= '
                            <button title="Checkout" class="btn btn-sm btn-warning"
                                data-id="'.$value->id.'" 
                                onclick="showCheckOutForm('.$value->id.');">
                            <i class="fa fa-sign-out"></i> 
                            </button>';
                    }
                    if(auth()->user()->can('room_assignments.delete')) {
                        $actions .= '
                            <button title="Delete" class="btn btn-sm btn-danger" 
                                data-id="'.$value->id.'" 
                                onclick="ShowConfirmDeleteForm('.$value->id.');">
                            <i class="fa-solid fa-trash-can"></i>
                            </button>';
                    }
                }
            }
            else{
                $actions = '';
            }
            
            $newData[] = [
                'id' => $value->id,
                'room' => $value->room->name,
                'guest' => $name,
                'check_in' => $checkIn,
                's_check_out' => $checkOut,
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function getBillingData($request)
    {
        
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = GuestBilling::where('room_assignment_id', $request->room_assignment_id);

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){ 
                    $query->orWhere("guest_billings.{$column['name']}", 'like', '%' . $search . '%'); 
                }  
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        $query->orderBy("guest_billings.$orderByCol", $orderBy);
            
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();

        $statuses = GeneralStatus::where('category', 'guest_billing_status')->get();
        
        $newData = [];
        foreach ($data as $value) {
            
            $currentStatus = $statuses->firstWhere('id', $value->status_id);

            if(auth()->user()->canany(['room_assignments.billing'])) {
                $statusList = '<div class="dt-modal-dropdown">
                        <button type="button" class="dt-modal-dropdown-btn btn btn-sm btn-outline-secondary" style="color:'.$currentStatus->color.'; background-color:'.$currentStatus->bg_color.'; border-color:'.$currentStatus->bg_color.';">
                            '.$currentStatus->name.' <i class="fa-solid fa-caret-down"></i>
                        </button>   
                        <ul class="dt-modal-dropdown-menu">';
                foreach ($statuses as $status) {
                    $statusList .= '<li>
                            <a data-id="'.$value->id.'" data-value="'.$status->name.'" data-status_id="'.$status->id.'" style="color: #212529;" class="dt-modal-dropdown-item single-status-dropdown" href="javascript:void(0)">
                                <i class="fa-solid fa-caret-right" style="color:'.$status->bg_color.';"></i> '.$status->name.'
                            </a>
                        </li>';
                }
                
                $statusList .= '</ul>
                    </div>';
            }
            else{
                $statusList = '';
            }

            if(auth()->user()->canany(['room_assignments.billing'])) {
                $paymentStatusName = ($value->is_paid == 1)?'PAID':'PENDING';
                $paymentStatus = '<div class="dt-modal-dropdown">
                        <button type="button" class="dt-modal-dropdown-btn btn btn-sm btn-outline-secondary">
                            '.$paymentStatusName.' <i class="fa-solid fa-caret-down"></i>
                        </button>   
                        <ul class="dt-modal-dropdown-menu">
                        <li>
                            <a data-value="1" data-id="'.$value->id.'" style="color: #212529;" class="dt-modal-dropdown-item single-payment-dropdown" href="javascript:void(0)">
                                PAID
                            </a>
                            <a data-value="0" data-id="'.$value->id.'" style="color: #212529;" class="dt-modal-dropdown-item single-payment-dropdown" href="javascript:void(0)">
                                PENDING
                            </a>
                        </li>';
                
                
                $paymentStatus .= '</ul>
                    </div>';
            }
            else{
                $paymentStatus = '';
            }
            
            $newData[] = [
                'checkbox' => '',
                'id' => $value->id,
                'transaction_datetime' => date('Y-m-d h:i:s a', strtotime($value->transaction_datetime)),
                'refno' => $value->refno,
                'category' => Str::upper($value->category),
                'item_name' => $value->item_name,
                'quantity' => $value->quantity,
                'unit_price' => $value->unit_price,
                'status' =>  $statusList,
                'payment_status' => $paymentStatus,
                'is_paid' => $value->is_paid,
                'status_id' => $value->status_id,
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function store($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();

            // Retrieve the existing record
            $room = Room::find($post['room_id']);

            // Check if the record exists
            if (!$room) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Room not found.'
                ], 404);
            }

            // Check if room status is available (room_status == 1)
            if ($room->room_status != 1) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Room is not available.'
                ], 400); // Using 400 Bad Request for this situation
            }

            // Retrieve the existing record
            $guest = Guest::find($post['customer_id']);

            // Check if the record exists
            if (!$guest) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Guest not found.'
                ], 404);
            }
            
            $sCheckOut = Carbon::createFromFormat('Y-m-d h:i A', $post['s_check_out'])->format('Y-m-d H:i:s');

            $data = new RoomAssignment();
            $data->customer_id = $post['customer_id'];
            $data->customer_name = $guest->title.' '.$guest->firstname.' '.$guest->lastname;
            $data->room_id = $post['room_id'];
            $data->room_number = $room->name;
            $data->check_in = Carbon::now()->format('Y-m-d H:i:s');
            $data->s_check_out = $sCheckOut;
            

            if ($data->save()) {
                // Record saved successfully

                //change availability of room to occupied
                $room->room_status = 2;
                if(!$room->save()){
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Failed to change room status.'
                    ], 400);
                }
                // Convert the role to an array
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                //For Analytics
                $analyticsData = array(
                    "counter" => 1,
                    "category_key" => "check_in"
                );
                $this->storeAnalytics($analyticsData);

                $commands = [
                    'get_customer',
                ];
        
                $conditions = [
                    ['field' => 'room_id', 'operator' => '=', 'value' => ''.$data->room_id.''],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);

                    // Run the artisan command for updating devices
                    Artisan::call('devices:updated', ['type' => 'guest']);
                    Artisan::call('devices:updated', ['type' => 'room']);
                });

                DB::commit();

                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

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

    public function checkout($request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();

            // Retrieve the existing record
            $data = RoomAssignment::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Retrieve the existing record
            $room = Room::find($data['room_id']);

            // Check if the record exists
            if (!$room) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Room not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();
            
            $data->check_out = Carbon::now()->format('Y-m-d H:i:s');
            $data->is_checkout = 1;
            
            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->raCheckOut($oldDataArray, $newDataArray, $this->activityName);

                $room->room_status = 1;
                if(!$room->save()){
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Failed to change room status.'
                    ], 400);
                }

                $commands = [
                    'get_customer',
                    'get_message',
                ];
        
                $conditions = [
                    ['field' => 'room_id', 'operator' => '=', 'value' => ''.$data->room_id.''],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);

                    // Run the artisan command for updating devices
                    Artisan::call('devices:updated', ['type' => 'guest']);
                    Artisan::call('devices:updated', ['type' => 'room']);
                });

                DB::commit();

                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

                return response()->json([
                    'status'=>'success',
                    'message'=>'Guest successfully checked out.'
                ], 200);
            } else {
                // Failed to save the record
                DB::rollBack(); 
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Guest was not checked out.'
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
            
            $data = RoomAssignment::where('id', $id)->first();

            if ($data) {
                // Retrieve the existing record
                $room = Room::find($data['room_id']);

                // Check if the record exists
                if (!$room) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Room not found.'
                    ], 404);
                }

                $room->room_status = 1;
                if(!$room->save()){
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Failed to change room status.'
                    ], 400);
                }

                $data->delete();

                $this->logHistoriesRepo->delete($id, $this->activityName);

                $commands = [
                    'get_customer',
                    'get_message',
                ];
        
                $conditions = [
                    ['field' => 'room_id', 'operator' => '=', 'value' => ''.$data->room_id.''],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);

                    // Run the artisan command for updating devices
                    Artisan::call('devices:updated', ['type' => 'guest']);
                    Artisan::call('devices:updated', ['type' => 'room']);
                });

                DB::commit();

                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');
                
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

    public function changeRoom($request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $data = RoomAssignment::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }
            
            // Retrieve the existing record
            $room = Room::find($post['room_id']);

            // Check if the record exists
            if (!$room) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Room not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();
            $oldRoomId = $data->room_id; 

            $data->room_id = $post['room_id'];
            $data->room_number = $room->name;

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                $room->room_status = 2;
                if(!$room->save()){
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Failed to change room status.'
                    ], 400);
                }

                // Retrieve the existing record
                $oldRoom = Room::find($post['current_room_id']);

                // Check if the record exists
                if (!$oldRoom) {
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Room not found.'
                    ], 404);
                }
                
                $oldRoom->room_status = 1;
                if(!$oldRoom->save()){
                    DB::rollBack(); 
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Failed to change room status.'
                    ], 400);
                }

                $commands = [
                    'get_customer',
                    'get_message',
                ];
        
                $conditions = [
                    'or' => [
                        ['field' => 'room_id', 'operator' => '=', 'value' => $data->room_id],
                        ['field' => 'room_id', 'operator' => '=', 'value' => $oldRoomId],
                    ],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);

                    // Run the artisan command for updating devices
                    Artisan::call('devices:updated', ['type' => 'guest']);
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
}