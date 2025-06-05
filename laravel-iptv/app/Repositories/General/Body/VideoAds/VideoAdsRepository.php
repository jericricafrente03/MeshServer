<?php 

namespace App\Repositories\General\Body\VideoAds;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\VideoAds\IVideoAdsRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\VideoAds\VideoAd;
use App\Models\General\Body\VideoAds\VideoAdsRoom;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VideoAdsRepository extends Controller implements IVideoAdsRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'video ads';
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
        $query = VideoAd::with('videoAdsRooms.room');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("video_ads.{$column['name']}", 'like', '%' . $search . '%');
                }
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        $query->orderBy("video_ads.$orderByCol", $orderBy);
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            // dd($value->device_group->name);
            $dateBg = (date('a', strtotime($value->created_at)) == 'pm')?'#8833ff':'#0d6efd';
            $date = date('Y-m-d h:i', strtotime($value->created_at)).' <a style="min-width: 5px; font-weight:bold; cursor: default; background-color: '.$dateBg.'; color: white;" type="button" class="btn btn-sm">
                <small>'.strtoupper(date('a', strtotime($value->created_at))).'</small>
            </a>';

            if($value->is_enable == 1){
                $is_enable = '✅';//'<span class="text-success">✔</span>';
                $toggleName = 'Toggle Disble';
                $toggleIcon = 'fa-toggle-off text-danger';
            }
            else{
                $is_enable = '❌';//<span class="text-danger">✖</span>';
                $toggleName = 'Toggle Enable';
                $toggleIcon = 'fa-toggle-on text-success';
            }
            
            if(auth()->user()->canany(['video_ads.view','video_ads.enable', 'video_ads.edit', 'video_ads.delete', 'video_ads.change_order'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                
                if(auth()->user()->can('video_ads.enable')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="toggleEnable('.$value->id.');"><i class="fa '.$toggleIcon.'"></i> '.$toggleName.'</a></li>';
                }

                if(auth()->user()->can('video_ads.change_order')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" class="me-1"
                        onclick="showChangeOrderModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-arrows-v text-primary me-1 ms-1"></i> Change Order</a></li>';
                }
                
                if(auth()->user()->can('video_ads.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }

                if(auth()->user()->can('video_ads.edit')) {
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
            
                if(auth()->user()->can('video_ads.delete')) {
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

            if($value->video_uri){
                // $video_uri = '<a href="' . asset($value->video_uri) . '" target="_blank"><img src="' . asset($value->img_thumbnail_uri) . '" alt="Image" style="width:50px; height:50px; object-fit:cover;" class="img img-thumbnail"></a>';
                $video_uri = '<a href="' . asset($value->video_uri) . '" target="_blank"><video src="' . asset($value->video_uri) . '" autoplay controls muted class="media-object img img-thumbnail" style="width:150px"></video></a>';
            }
            else{
                $video_uri = '';
            }

            $rooms = [];
            foreach ($value->videoAdsRooms as $videoAdsRoom) {
                $rooms[] = $videoAdsRoom->room->name; // Add the `room` attribute to the array
            }
            $roomString = implode(', ', $rooms);

            $newData[] = [
                'checkbox' => '',
                'id' => $value->id,
                'video_uri' => $video_uri,
                'name' => $value->name,
                'rooms' => $roomString,
                'date' => $date,
                'is_enable' => $is_enable,
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
            $filePath = '';
            // Log::info('Upload file size: ' . request()->file('video_uri')->getSize());
            // Log::info('PHP limits: ' . ini_get('post_max_size') . ' ' . ini_get('upload_max_filesize'));

            // Specify a custom directory
            $customDirectory = 'upload/video_ads';
            // Check if video_uri exists in the request
            if ($request->hasFile('video_uri')) {
                // Use the trait to upload the file to the custom directory
                $filePath = $this->uploadFile($request->file('video_uri'), $customDirectory);
            }
    
            $data = new VideoAd();
            $data->name = $post['name'];
            $data->is_enable = 0;
            $data->order_no = $post['order_no'];
            
            if($filePath){
                $relativeUrl = 'storage/' . $filePath;
                $data->video_uri = $relativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();
                
                foreach ($post['room_id'] as $roomId) {
                    $videoAdsRoom[] = [
                        'video_ads_id' => $data->id,
                        'room_id' => $roomId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            
                // Insert data in bulk
                VideoAdsRoom::insert($videoAdsRoom);

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                DB::commit();

                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been saved.'
                ], 201);
            } else {
                // Failed to save the record

                DB::rollBack(); 
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not saved.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function toggleEnable($request)
    {
        try{
            $data = VideoAd::find($request->id);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->is_enable = ($data->is_enable == 1)?'0':'1';

            if ($data->save()) {
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_video_ads'
                ];

                $conditions = [
                    [
                        'has' => 'room', // The `has` key indicates a `whereHas` condition
                        'relation' => 'room.videoAdsRooms', // Name of the relation
                        'where' => [
                            ['field' => 'video_ads_id', 'operator' => '=', 'value' => $data->id],
                        ],
                    ],
                ];
                
                // Queue the job to be executed after commit
                DB::afterCommit(function () use ($commands, $conditions) {
                    // Dispatch the job in the background
                    SendDataToDevicesJob::dispatch($commands, $conditions);
                });

                DB::commit();

                // Start the queue worker temporarily to process the job
                shell_exec(base_path().'/public/bash-scripts/run_artisan_queue_work.sh > /dev/null 2>&1 &');

                $toggleMessage = ($data->is_enable == 1)?'on':'off';
                return response()->json([
                    'status'=>'success',
                    'message'=>'Toggle '.$toggleMessage.'.'
                ], 201);
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

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $filePath = '';
            $videoAdsRoom = [];
            
            // Retrieve the existing record
            $data = VideoAd::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

           // Specify a custom directory
           $customDirectory = 'upload/video_ads';
           // Check if video_uri exists in the request
           if ($request->hasFile('video_uri')) {
               if($data->video_uri != ''){
                   $path = str_replace('storage/', '', $data->video_uri); 
                   $this->deleteFile($path);
               }
               // Use the trait to upload the file to the custom directory
               $filePath = $this->uploadFile($request->file('video_uri'), $customDirectory);
           }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->name = $post['name'];

            if($filePath){
                $relativeUrl = 'storage/' . $filePath;
                $data->video_uri = $relativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Delete existing records for the given video_ads_id
                VideoAdsRoom::where('video_ads_id', $data->id)->delete();
                foreach ($post['room_id'] as $roomId) {
                    $videoAdsRoom[] = [
                        'video_ads_id' => $data->id,
                        'room_id' => $roomId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            
                // Insert data in bulk
                VideoAdsRoom::insert($videoAdsRoom);

                // Prepare the commands
                $commands = [
                    'get_video_ads'
                ];

                $conditions = [
                    [
                        'has' => 'room', // The `has` key indicates a `whereHas` condition
                        'relation' => 'room.videoAdsRooms', // Name of the relation
                        'where' => [
                            ['field' => 'video_ads_id', 'operator' => '=', 'value' => $data->id],
                        ],
                    ],
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
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not updated.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
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
            // Prepare the commands
            $commands = [
                'get_video_ads'
            ];
            
            $conditions = [];

            if($input['type'] == "single"){
                $data = VideoAd::find($input['id']);
                if(!$data){
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Record not found.'
                    ], 404);
                }
                
                if ($data) {
                    // Remove file
                    if($data->video_uri != ''){
                        $path = str_replace('storage/', '', $data->video_uri); 
                        $this->deleteFile($path);
                    }
                    $data->delete();

                    $this->logHistoriesRepo->delete($data->id, $this->activityName);
                    
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
                    
                    $data = VideoAd::where('id', $id)->first();
                    
                    if (!$data) {
                        continue; // Skip to the next iteration
                    }

                    // Remove file
                    if($data->video_uri != ''){
                        $path = str_replace('storage/', '', $data->video_uri); 
                        $this->deleteFile($path);
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

    public function changeOrder($request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $data = VideoAd::find($post['id']);

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

                // Prepare the commands
                $commands = [
                    'get_video_ads'
                ];

                $conditions = [
                    [
                        'has' => 'room', // The `has` key indicates a `whereHas` condition
                        'relation' => 'room.videoAdsRooms', // Name of the relation
                        'where' => [
                            ['field' => 'video_ads_id', 'operator' => '=', 'value' => $data->id],
                        ],
                    ],
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
}