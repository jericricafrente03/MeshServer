<?php

namespace App\Repositories\General\Body\Messages\BroadcastMessages;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Messages\BroadcastMessages\IEmergencyRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Messages\BroadcastMessage;
use Illuminate\Support\Facades\DB;

class EmergencyRepository extends Controller implements IEmergencyRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'emergency';
    }

    public function getData($request)
    {
        
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = BroadcastMessage::with('type', 'device_group')->where('broadcast_type_id', 48);

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'type' && $column['name'] !== 'device_group') {
                        $query->orWhere("broadcast_messages.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }
            }
            
            $query->orWhereHas('type', function ($query) use ($search) {
                $query->where("general_statuses.name", "like", '%' . $search . '%');
            });

            $query->orWhereHas('device_group', function ($query) use ($search) {
                $query->where("device_categories.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'type') {
            $query->join('general_statuses', 'general_statuses.id', '=', 'broadcast_messages.type_id')
                ->select('broadcast_messages.*', 'general_statuses.name as type_name')
                ->distinct()
                ->orderBy('general_statuses.name', $orderBy);
        } 
        elseif ($orderByCol === 'device_group') {
            $query->leftJoin('device_categories', 'device_categories.id', '=', 'broadcast_messages.device_category_id')
                ->select('broadcast_messages.*', 'device_categories.name as group_name')
                ->distinct()
                ->orderBy('device_categories.name', $orderBy);
        }
        else {
            $query->orderBy("broadcast_messages.$orderByCol", $orderBy);
        }

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            // dd($value->device_group->name);
            $dateBg = (date('a', strtotime($value->updated_at)) == 'pm')?'#8833ff':'#0d6efd';
            $date = date('Y-m-d h:i', strtotime($value->updated_at)).' <a style="min-width: 5px; font-weight:bold; cursor: default; background-color: '.$dateBg.'; color: white;" type="button" class="btn btn-sm">
                <small>'.strtoupper(date('a', strtotime($value->updated_at))).'</small>
            </a>';
            
            if(auth()->user()->canany(['emergencies.view','emergencies.resend', 'emergencies.delete'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                
                if(auth()->user()->can('emergencies.resend')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="clickResend('.$value->id.');"><i class="fa-solid fa-rotate-right text-primary"></i> Resend</a></li>';
                }
                
                if(auth()->user()->can('emergencies.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
            
                if(auth()->user()->can('emergencies.delete')) {
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
                'checkbox' => '',
                'id' => $value->id,
                'device_group' => $value->device_group->name,
                'duration' => $value->duration,
                'message' => $value->message,
                'type' => '<a style="background-color: '.$value->type->bg_color.'; color: '.$value->type->color.'; min-width: 150px; font-weight:bold; cursor: default;" type="button" class="btn btn-default btn-sm px-3 d-flex justify-content-center align-items-center">
                        <small>'.ucfirst($value->type->name).'</small>
                    </a>',
                'date' => $date,
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
    
            $data = new BroadcastMessage();
            $data->duration = $post['duration'];
            $data->message = $post['message'];
            $data->broadcast_type_id = 48;
            $data->type_id = $post['type_id'];

            if($post['type_id'] == '41'){
                $data->device_category_id = $post['category_id'];
            }

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                switch ($post['type_id']) {
                    case '41': // Process rooms for a specific category
                        $dataArray['category_id'] = $post['category_id'];
                        $conditions = [
                            [
                                'has' => 'category', // Check if devices have a specific category
                                'relation' => 'category', // Relationship name
                                'where' => [
                                    ['field' => 'id', 'operator' => '=', 'value' => $post['category_id']],
                                ],
                            ],
                        ];
                        break;
                
                    default: // Process all rooms
                        $conditions = [
                            //empty conditions to get all
                        ];
                        break;
                }

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_broadcast_messaging?id='.$data->id,
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

    public function delete($request)
    {   
        try {
            DB::beginTransaction();
            $input = $request->all();

            if($input['type'] == "single"){
                $data = BroadcastMessage::find($input['id']);
                if(!$data){
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Record not found.'
                    ], 404);
                }
                
                if ($data) {
                    $data->delete();

                    $this->logHistoriesRepo->delete($data->id, $this->activityName);
                
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
            }
            else{
                $errors = []; // Array to store any errors
                $success = true; // Flag to check overall success
                $ids = $input['ids'];
                // dd($ids);
                foreach($ids as $id){
                    
                    $data = BroadcastMessage::where('id', $id)->first();
                    
                    if (!$data) {
                        continue; // Skip to the next iteration
                    }

                    if(!$data->delete()){
                        $errors[] = "Failed to delete record with ID: $id";
                        $success = false;
                    }
                    else{
                        $this->logHistoriesRepo->delete($data->id, $this->activityName);
                    }
                }

                if ($success) {
                    // Record saved successfully
                    DB::commit();
                    
                    return response()->json([
                        'status'=>'success',
                        'message'=>'Record(s) has been deleted.'
                    ], 200);
                } else {
                    DB::rollBack(); 
                    // Failed to save the record
                    return response()->json([
                        'status'=>'danger',
                        'message'=>'Record(s) rollback due to: '. implode(', ', $errors)
                    ], 404);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function resend($request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            $data = BroadcastMessage::find($input['id']);
            $dataArray = $data->toArray();

            switch ($data->type_id) {
                case '41': // Process rooms for a specific category
                    $dataArray['category_id'] = $data->device_category_id;
                    $conditions = [
                        [
                            'has' => 'category', // Check if devices have a specific category
                            'relation' => 'category', // Relationship name
                            'where' => [
                                ['field' => 'id', 'operator' => '=', 'value' => $data->device_category_id],
                            ],
                        ],
                    ];
                    break;
            
                default: // Process all rooms
                    $conditions = [
                        //empty conditions to get all
                    ];
                    break;
            }

            // Remove to the array
            unset($dataArray['updated_at']);
            unset($dataArray['created_at']);

            $this->logHistoriesRepo->store($dataArray, $this->activityName);

            // Prepare the commands
            $commands = [
                'get_broadcast_messaging?id='.$data->id,
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
                'message'=>'Resending complete.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }
}