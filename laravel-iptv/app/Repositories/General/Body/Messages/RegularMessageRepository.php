<?php

namespace App\Repositories\General\Body\Messages;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Messages\IRegularMessageRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\ProcessMessageRecipients;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Messages\MessageRecipient;
use App\Models\General\Body\Messages\RegularMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegularMessageRepository extends Controller implements IRegularMessageRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'regular messages';
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
        $query = MessageRecipient::whereHas('message', function ($q) {
                $q->whereNull('deleted_at'); // Only include messages that are not soft-deleted
            })->with(['message', 'room', 'status']);

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'type' && $column['name'] !== 'room' && $column['name'] !== 'from' && $column['name'] !== 'status' && $column['name'] !== 'to' && $column['name'] !== 'subject') {
                        $query->orWhere("message_recipients.{$column['name']}", 'like', '%' . $search . '%');
                    }

                    // Special handling for 'from'
                    if ($column['name'] === 'from') {
                        $query->orWhereHas('message', function ($query) use ($search) {
                            $query->where('from', 'like', '%' . $search . '%');
                        });
                    }
                    if ($column['name'] === 'subject') {
                        $query->orWhereHas('message', function ($query) use ($search) {
                            $query->where('subject', 'like', '%' . $search . '%');
                        });
                    }
                }
            }
            
            $query->orWhereHas('status', function ($query) use ($search) {
                $query->where("general_statuses.name", "like", '%' . $search . '%');
            });
            $query->orWhereHas('message.type', function ($query) use ($search) {
                $query->where("general_statuses.name", "like", '%' . $search . '%');
            });
            $query->orWhereHas('room', function ($query) use ($search) {
                $query->where("rooms.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'room') {
            $query->join('rooms', 'rooms.id', '=', 'message_recipients.room_id')
                ->select('message_recipients.*', 'rooms.name as room_name')
                ->distinct()
                ->orderBy('rooms.name', $orderBy);
        } 
        elseif ($orderByCol === 'status') {
            $query->join('general_statuses', 'message_recipients.status_id', '=', 'general_statuses.id')
                ->select('message_recipients.*', 'general_statuses.name as status_name')
                ->distinct()
                ->orderBy('general_statuses.name', $orderBy);
        }
        elseif ($orderByCol === 'from') {
            $query->join('regular_messages', 'regular_messages.id', '=', 'message_recipients.regular_message_id')
                ->select('message_recipients.*', 'regular_messages.from as message_from')
                ->distinct()
                ->orderBy('regular_messages.from', $orderBy);
        }
        elseif ($orderByCol === 'type') {
            $query->join('regular_messages', 'regular_messages.id', '=', 'message_recipients.regular_message_id')
                ->join('general_statuses as types', 'types.id', '=', 'regular_messages.type_id')
                ->select('message_recipients.*', 'types.name as type_name')
                ->distinct()
                ->orderBy('types.name', $orderBy);
        } 
        elseif ($orderByCol === 'subject') {
            $query->join('regular_messages', 'regular_messages.id', '=', 'message_recipients.regular_message_id')
                ->select('message_recipients.*', 'regular_messages.subject as message_subject')
                ->distinct()
                ->orderBy('regular_messages.subject', $orderBy);
        }
        elseif ($orderByCol === 'date') {
            $query->join('regular_messages', 'regular_messages.id', '=', 'message_recipients.regular_message_id')
                ->select('message_recipients.*', 'regular_messages.created_at as message_created_at')
                ->distinct()
                ->orderBy('regular_messages.created_at', $orderBy);
        }
        else {
            $query->orderBy("message_recipients.$orderByCol", $orderBy);
        }

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            
            $dateBg = (date('a', strtotime($value->created_at)) == 'pm')?'#8833ff':'#0d6efd';
            $date = date('Y-m-d h:i', strtotime($value->created_at)).' <a style="min-width: 5px; font-weight:bold; cursor: default; background-color: '.$dateBg.'; color: white;" type="button" class="btn btn-sm">
                <small>'.strtoupper(date('a', strtotime($value->created_at))).'</small>
            </a>';
            
            if(auth()->user()->canany(['regular_messages.view','regular_messages.edit', 'regular_messages.delete'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                
                if(auth()->user()->can('regular_messages.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('regular_messages.edit')) {
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('regular_messages.delete')) {
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
                'from' => $value->message->from,
                'room' => $value->room->name,
                'subject' => $value->message->subject,
                'type' => '<a style="background-color: '.$value->message->type->bg_color.'; color: '.$value->message->type->color.'; min-width: 150px; font-weight:bold; cursor: default;" type="button" class="btn btn-default btn-sm px-3 d-flex justify-content-center align-items-center">
                        <small>'.ucfirst($value->message->type->name).'</small>
                    </a>',
                'status' => '<a style="background-color: '.$value->status->bg_color.'; color: '.$value->status->color.'; min-width: 150px; font-weight:bold; cursor: default;" type="button" class="btn btn-default btn-sm px-3 d-flex justify-content-center align-items-center">
                        <small>'.ucfirst($value->status->name).'</small>
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
    
            $data = new RegularMessage();
            $data->from = $post['from'];
            $data->body = $post['body'];
            $data->subject = $post['subject'];
            $data->type_id = $post['type_id'];

            if($post['type_id'] == '22'){
                $data->device_category_id = $post['category_id'];
            }

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                switch ($post['type_id']) {
                    case '21':
                        $msg = new MessageRecipient();
                        $msg->regular_message_id = $data->id;
                        $msg->room_id = $post['room_id'];
                        $msg->status_id = 31;//New
                        
                        if (!$msg->save()) {
                            DB::rollBack(); 
               
                            return response()->json([
                                'status'=>'warning',
                                'message'=>'Record is not saved.'
                            ], 404);
                        }

                        $dataArray['room_id'] = $post['room_id'];

                        $conditions = [
                            [
                                'has' => 'room', // The `has` key indicates a `whereHas` condition
                                'relation' => 'room', // Name of the relation
                                'where' => [
                                    ['field' => 'id', 'operator' => '=', 'value' => $post['room_id']],
                                ],
                            ],
                        ];
                        break;
                    case '22': // Process rooms for a specific category
                        ProcessMessageRecipients::dispatch([
                            'id' => $data->id,
                            // Additional fields if needed
                        ], $post['category_id']);

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
                        ProcessMessageRecipients::dispatch([
                            'id' => $data->id,
                            // Additional fields if needed
                        ], null, true); // `true` indicates processing all rooms
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
                    'get_message',
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
                $messageRecipient = MessageRecipient::find($input['id']);
                if(!$messageRecipient){
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Record not found.'
                    ], 404);
                }
                
                $data = RegularMessage::where('id', $messageRecipient->regular_message_id)->first();
                
                if ($data) {
                    $data->delete();

                    $this->logHistoriesRepo->delete($messageRecipient->regular_message_id, $this->activityName);
                    // Prepare the commands
                    $commands = [
                        'get_message'
                    ];

                    $conditions = [
                        ['field' => 'room_id', 'operator' => '=', 'value' => ''.$messageRecipient->room_id.'']
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
                    
                    $mr = MessageRecipient::where('id', $id)->first();
                    
                    if (!$mr) {
                        continue; // Skip to the next iteration
                    }

                    $data = RegularMessage::where('id', $mr->regular_message_id)->first();

                    if(!$data->delete()){
                        $errors[] = "Failed to delete record with ID: $id";
                        $success = false;
                    }
                    else{
                        $this->logHistoriesRepo->delete($mr->regular_message_id, $this->activityName);

                        // Prepare the commands
                        $commands = [
                            'get_message'
                        ];

                        switch ($data->type_id) {
                            case '21':
                                $conditions = [
                                    ['field' => 'room_id', 'operator' => '=', 'value' => ''.$mr->room_id.''],
                                ];
                                break;
                            case '21':
                                $conditions = [
                                    ['field' => 'category_id', 'operator' => '=', 'value' => ''.$data->device_category_id.''],
                                ];
                                break;
                            default:
                                $conditions = [
                                    //empty conditions to get all
                                ];
                                break;
                        }
                        

                        // Queue the job to be executed after commit
                        DB::afterCommit(function () use ($commands, $conditions) {
                            // Dispatch the job in the background
                            SendDataToDevicesJob::dispatch($commands, $conditions);
                        });
                    }
                }

                if ($success) {
                    // Record saved successfully
                    DB::commit();

                    // Start the queue worker temporarily to process the job
                    shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');
                    
                    return response()->json([
                        'status'=>'success',
                        'message'=>'Record(s) has been updated.'
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

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $messageRecipient = MessageRecipient::find($post['id']);

            // Check if the record exists
            if (!$messageRecipient) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            $data = RegularMessage::find($messageRecipient->regular_message_id);

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->from = $post['from'];
            $data->subject = $post['subject'];
            $data->body = $post['body'];

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_message'
                ];

                switch ($data->type_id) {
                    case '21':
                        $conditions = [
                            ['field' => 'room_id', 'operator' => '=', 'value' => ''.$messageRecipient->room_id.''],
                        ];
                        break;
                    case '21':
                        $conditions = [
                            ['field' => 'category_id', 'operator' => '=', 'value' => ''.$data->device_category_id.''],
                        ];
                        break;
                    default:
                        $conditions = [
                            //empty conditions to get all
                        ];
                        break;
                }
                

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
}