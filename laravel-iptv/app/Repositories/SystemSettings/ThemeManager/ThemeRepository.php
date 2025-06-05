<?php

namespace App\Repositories\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\ThemeManager\IThemeRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\SystemSettings\ThemeManager\DefaultApp;
use App\Models\SystemSettings\ThemeManager\Theme;
use App\Models\SystemSettings\ThemeManager\ThemeApplication;
use App\Models\SystemSettings\ThemeManager\ThemeRoom;
use App\Models\SystemSettings\ThemeManager\ThemeZone;
use App\Models\SystemSettings\ThemeManager\Zone;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThemeRepository extends Controller implements IThemeRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'themes';
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
        $query = Theme::with('themeRooms.room');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("themes.{$column['name']}", 'like', '%' . $search . '%');
                }
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        $query->orderBy("themes.$orderByCol", $orderBy);
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {

            if($value->is_default == 1){
                $defaultClass = 'btn-success';
                $defaultIcon = 'fa-solid fa-star';
                $defaultTitle = 'Already Selected as Default Theme';
                $defaultDisabled = 'disabled';
            }
            else{
                $defaultClass = 'btn-warning';
                $defaultIcon = 'fa-regular fa-star';
                $defaultTitle = 'Click to set as Default Theme';
                $defaultDisabled = '';
            }
            
            if(auth()->user()->canany(['themes.edit_application', 'themes.assign_room', 'themes.edit_theme', 'themes.edit_theme_zone', 'themes.default', 'themes.delete'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('themes.assign_room')) {
                    $actions .= '<button title="Assign room to this theme" class="btn btn-secondary '.$defaultDisabled.' btn-sm me-2 fixed-size-btn" 
                            onclick="showAssignRoomModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-door-closed"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.edit_application')) {
                    $actions .= '<button title="Edit Applications" class="btn btn-success btn-sm me-2 fixed-size-btn" 
                            onclick="showThemeApplicationsModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-brands fa-android"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.edit_theme')) {
                    $actions .= '<button title="Edit Theme" class="btn btn-primary btn-sm me-2 fixed-size-btn" 
                            onclick="showEditThemeModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-gear"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.edit_theme_zone')) {
                    $actions .= '<button title="Open Theme Zone List" class="btn btn-info btn-sm me-2 fixed-size-btn" 
                            onclick="showThemeZonesModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-table-list"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.delete')) {
                    $actions .= '<button title="Delete Theme" class="btn btn-danger '.$defaultDisabled.' btn-sm me-2 fixed-size-btn" 
                            onclick="ShowConfirmDeleteForm('.$value->id.');">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.default')) {
                    $actions .= '<button title="'.$defaultTitle.'" class="btn '.$defaultClass.' '.$defaultDisabled.' btn-sm me-2 fixed-size-btn" 
                            onclick="toggleDefault('.$value->id.', '.$value->is_default.');">
                            <i class="'.$defaultIcon.'"></i>
                        </button>';
                }

                $actions .= '</div>';
            }
            else{
                $actions = '';
            }

            $newData[] = [
                'id' => $value->id,
                'name' => $value->name,
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
            $zoneData = [];
            $applicationData = [];
            $now = Carbon::now();

            $defaultApp = DefaultApp::count();

            // Check if the record exists
            if ($defaultApp == 0) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Create Default App first!'
                ], 404);
            }

            $zoneCount = Zone::count();

            // Check if the record exists
            if ($zoneCount == 0) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Create Zones first!'
                ], 404);
            }

            // Specify a custom directory
            $customDirectory = 'upload/themes';
            // Check if video_uri exists in the request
            if ($request->hasFile('bg')) {
                // Use the trait to upload the file to the custom directory
                $filePath = $this->uploadFile($request->file('bg'), $customDirectory);
            }

            $themeCount = Theme::count();
    
            $data = new Theme();
            $data->name = $post['name'];
            $data->is_default = ($themeCount > 0)?0:1;
            
            if($filePath){
                $relativeUrl = 'storage/' . $filePath;
                $data->bg_uri = $relativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                $zones = Zone::all();

                // Attach zones to the newly created theme
                foreach ($zones as $zone) {
                    $zoneData[$zone->id] = [
                        'bg_uri' => null,
                        'text_color' => '#33333f',
                        'active_text_color' => '#bbbbbb',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];      
                }
                $data->zones()->attach($zoneData);

                $applications = DefaultApp::all();
                $orderNo = 1;
                // Attach applications to the newly created theme
                foreach ($applications as $application) {
                    $applicationData[$application->id] = [
                        'icon' => null,
                        'active_icon' => null,
                        'text_color' => '#33333f',
                        'active_text_color' => '#bbbbbb',
                        'order_no' => $application->is_enable == 1 ? $orderNo : null,
                        'is_enable' => $application->is_enable,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $orderNo++;
                }
                $data->applications()->attach($applicationData);

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

    public function toggleDefault($request)
    {
        try{
            DB::beginTransaction();
            $presentDefault = Theme::where('is_default', 1)->first();
            $presentDefault->is_default = 0;
            $presentDefault->save();

            $data = Theme::find($request->id);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->is_default = 1;

            if ($data->save()) {
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                ThemeRoom::where('theme_id', $data->id)->delete();

                // Prepare the commands
                $commands = [
                    'get_theme'
                ];

                $conditions = [
                
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
                    'message'=>'Set as Default.'
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

    public function updateTheme($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $filePath = '';
            
            // Retrieve the existing record
            $data = Theme::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            $customDirectory = 'upload/themes';
            // Check if video_uri exists in the request
            if ($request->hasFile('bg')) {
                if($data->bg_uri != ''){
                    $path = str_replace('storage/', '', $data->bg_uri); 
                    $this->deleteFile($path);
                }
                $filePath = $this->uploadFile($request->file('bg'), $customDirectory);
            }
            

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->name = $post['name'];
            if($filePath){
                $relativeUrl = 'storage/' . $filePath;
                $data->bg_uri = $relativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_theme'
                ];
                
                if($data->is_default === 0){
                    $conditions = [
                        [
                            'has' => 'room', // The `has` key indicates a `whereHas` condition
                            'relation' => 'room.themeRooms', // Name of the relation
                            'where' => [
                                ['field' => 'theme_id', 'operator' => '=', 'value' => $data->id],
                            ],
                        ],
                    ];
                }
                else{
                    $conditions = [];
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

    public function getThemeZoneData($request)
    {
        
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = ThemeZone::with('zones')->where('theme_id', $request->theme_id);

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'zones') {
                        $query->orWhere("theme_zones.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }
            }
            $query->orWhereHas('zones', function ($query) use ($search) {
                $query->where("zones.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'zones') {
            $query->join('zones', 'theme_zones.zone_id', '=', 'zones.id')
                ->select('theme_zones.*', 'zones.name as zone_name')
                ->distinct()
                ->orderBy('zones.name', $orderBy);
        }
        else {
            $query->orderBy("theme_zones.$orderByCol", $orderBy);
        }
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            
            if(auth()->user()->canany(['themes.edit_theme_zone'])) {
                $actions = '<div class="d-flex order-actions">';


                if(auth()->user()->can('themes.edit_theme_zone')) {
                    $actions .= '<button title="Edit Theme Zone" class="btn btn-primary btn-sm me-2 fixed-size-btn" 
                            onclick="showEditThemeZoneModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-gear"></i>
                        </button>';
                }

                $actions .= '</div>';
            }
            else{
                $actions = '';
            }

            $newData[] = [
                'id' => $value->id,
                'zones' => $value->zones->name,
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function updateThemeZone($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $filePath = '';
            
            // Retrieve the existing record
            $data = ThemeZone::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            if($post['is_null'] == 0){
                $customDirectory = 'upload/theme_zones';
                // Check if video_uri exists in the request
                if ($request->hasFile('bg')) {
                    if($data->bg_uri != ''){
                        $path = str_replace('storage/', '', $data->bg_uri); 
                        $this->deleteFile($path);
                    }
                    $filePath = $this->uploadFile($request->file('bg'), $customDirectory);
                }
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->text_color = $post['text_color'];
            $data->active_text_color = $post['active_text_color'];
            if($post['is_null'] == 1){
                $data->bg_uri = null;
            }
            if($post['is_null'] == 0){
                if($filePath){
                    $relativeUrl = 'storage/' . $filePath;
                    $data->bg_uri = $relativeUrl;
                }
            }

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $activity = 'Zone = '.$data->id.' of Theme = '.$data->theme_id;
                $this->logHistoriesRepo->customUpdate($oldDataArray, $newDataArray, $activity);
                
                // Prepare the commands
                $commands = [
                    'get_theme'
                ];

                $themeData = Theme::find($data->theme_id);
                
                if($themeData->is_default === 0){
                    $conditions = [
                        [
                            'has' => 'room', // The `has` key indicates a `whereHas` condition
                            'relation' => 'room.themeRooms', // Name of the relation
                            'where' => [
                                ['field' => 'theme_id', 'operator' => '=', 'value' => $data->theme_id],
                            ],
                        ],
                    ];
                }
                else{
                    $conditions = [];
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

            $id = $input['id'];
            
            $data = Theme::findOrFail($id);

            if ($data->is_default == 1) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record is default, cannot be deleted.'
                ], 404);
            }
            
            if ($data) {
                // Remove file
                if($data->bg_uri != ''){
                    $bgPath = str_replace('storage/', '', $data->bg_uri); 
                    $this->deleteFile($bgPath);
                }

                // Collect all bg_uri values (excluding null) before deleting the theme
                $bgUris = $data->zones()
                    ->whereNotNull('bg_uri') // Exclude null values
                    ->pluck('bg_uri')
                    ->toArray();
                
                $iconUris = $data->theme_applications()
                    ->whereNotNull('icon') // Exclude null values
                    ->pluck('icon')
                    ->toArray();
                $activeIconUris = $data->theme_applications()
                    ->whereNotNull('active_icon') // Exclude null values
                    ->pluck('active_icon')
                    ->toArray();
                
                $data->delete();

                // Loop through the bg_uri array and delete the files
                foreach ($bgUris as $bgUri) {
                    $tzBgUri = str_replace('storage/', '', $bgUri); 
                    $this->deleteFile($tzBgUri);
                }       

                foreach ($iconUris as $iconUri) {
                    $taIconUri = str_replace('storage/', '', $iconUri); 
                    $this->deleteFile($taIconUri);
                }  

                foreach ($activeIconUris as $activeIconUri) {
                    $taActiveIconUris = str_replace('storage/', '', $activeIconUri); 
                    $this->deleteFile($taActiveIconUris);
                }  

                $this->logHistoriesRepo->delete($id, $this->activityName);
                // Prepare the commands
                $commands = [
                    'get_theme'
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
    
    public function assignRoom($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $roomIds = $post['room_id'] ?? [];
            
            // Retrieve the existing record
            $data = ThemeRoom::where('theme_id', $post['id'])->get();

            $oldDataArray = $data->toArray();
            
            // Detach rooms from other themes before assigning to the current theme
            if (!empty($roomIds)) {
                ThemeRoom::whereIn('room_id', $roomIds)->delete();
            }

            // Check if the record exists
            if ($data->isEmpty()) {
                // No records found, insert new ones
                if (!empty($post['room_id'])) {
                    foreach ($post['room_id'] as $roomId) {
                        ThemeRoom::insert([
                            'theme_id' => $post['id'],
                            'room_id'  => $roomId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            else {
                // Records exist, delete all and insert new ones
                ThemeRoom::where('theme_id', $post['id'])->delete();
        
                if (!empty($post['room_id'])) {
                    foreach ($post['room_id'] as $roomId) {
                        ThemeRoom::insert([
                            'theme_id' => $post['id'],
                            'room_id'  => $roomId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            $newDataArray = ThemeRoom::where('theme_id', $post['id'])->get()->toArray();
            $activity = 'Rooms for Theme = '.$post['id'];
            $this->logHistoriesRepo->themeRoom($oldDataArray, $newDataArray, $activity);

            // Prepare the commands
            $commands = [
                'get_theme'
            ];

            $conditions = [
                
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
            
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function getThemeApplicationData($request)
    {
        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        // get data from products table
        $query = ThemeApplication::with('application')->where('theme_id', $request->theme_id)
            ->whereHas('application', function ($query) {
                $query->where('is_enable', 1);
            });
       
        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'application') {
                        $query->orWhere("theme_applications.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }
            }
            $query->orWhereHas('application', function ($query) use ($search) {
                $query->where("default_apps.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'application') {
            $query->join('default_apps', 'theme_applications.application_id', '=', 'default_apps.id')
                ->select('theme_applications.*', 'default_apps.name as app_name')
                ->distinct()
                ->orderBy('default_apps.name', $orderBy);
        }
        else {
            $query->orderBy("theme_applications.$orderByCol", $orderBy);
        }
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {

            if($value->is_enable==1){
                $enableClass = 'btn-success';
                $enableIcon = 'fa-solid fa-circle';
                $enableTitle = 'Click to Disable as Default APP';
            }
            else{
                $enableClass = 'btn-danger';
                $enableIcon = 'fa-solid fa-xmark';
                $enableTitle = 'Click to Enable as Default APP';
            }
            
            if(auth()->user()->canany(['themes.edit_theme_application', 'themes.enable_theme_application'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('themes.enable_theme_application')) {
                    $actions .= '<button title="'.$enableTitle.'" class="btn '.$enableClass.' btn-sm me-2 fixed-size-btn" 
                            onclick="toggleEnable('.$value->id.', '.$value->is_enable.');">
                            <i class="'.$enableIcon.'"></i>
                        </button>';
                }

                if(auth()->user()->can('themes.edit_theme_application')) {
                    $actions .= '<button title="Edit Theme Application" class="btn btn-primary btn-sm me-2 fixed-size-btn" 
                            onclick="showEditThemeApplicationModal('.$value->id.', '.htmlspecialchars(json_encode($value)).', '.$request->theme_id.');">
                            <i class="fa-solid fa-gear"></i>
                        </button>';
                }

                $actions .= '</div>';
            }
            else{
                $actions = '';
            }
            
            $newData[] = [
                'id' => $value->id,
                'application' => $value->application->name,
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function updateThemeApplication($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $filePathIcon = '';
            $filePathActiveIcon = '';
            
            // Retrieve the existing record
            $data = ThemeApplication::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Specify a custom directory
            $iconCustomDirectory = 'upload/theme_applications/icon';
            $activeIconCustomDirectory = 'upload/theme_applications/active_icon';
            
            if ($request->hasFile('icon')) {
                if($data->icon != ''){
                    $iconPath = str_replace('storage/', '', $data->icon); 
                    $this->deleteFile($iconPath);
                }
                $filePathIcon = $this->uploadFile($request->file('icon'), $iconCustomDirectory);
            }
            if ($request->hasFile('active_icon')) {
                if($data->active_icon != ''){
                    $activeIconPath = str_replace('storage/', '', $data->active_icon); 
                    $this->deleteFile($activeIconPath);
                }
                $filePathActiveIcon = $this->uploadFile($request->file('active_icon'), $activeIconCustomDirectory);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->order_no = $post['order_no'];
            $data->text_color = $post['text_color'];
            $data->active_text_color = $post['active_text_color'];

            if($filePathIcon){
                $relativeUrlIcon = 'storage/' . $filePathIcon;
                $data->icon = $relativeUrlIcon;
            }
            if($filePathActiveIcon){
                $relativeUrlActiveIcon = 'storage/' . $filePathActiveIcon;
                $data->active_icon = $relativeUrlActiveIcon;
            }

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $activity = 'Application = '.$data->id.' of Theme = '.$data->theme_id;
                $this->logHistoriesRepo->customUpdate($oldDataArray, $newDataArray, $activity);

                // Prepare the commands
                $commands = [
                    'get_theme'
                ];

                $themeData = Theme::find($data->theme_id);
                
                if($themeData->is_default === 0){
                    $conditions = [
                        [
                            'has' => 'room', // The `has` key indicates a `whereHas` condition
                            'relation' => 'room.themeRooms', // Name of the relation
                            'where' => [
                                ['field' => 'theme_id', 'operator' => '=', 'value' => $data->theme_id],
                            ],
                        ],
                    ];
                }
                else{
                    $conditions = [];
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
                if ($filePathActiveIcon) {
                    Storage::disk('public')->delete($filePathActiveIcon);
                }
                if ($filePathIcon) {
                    Storage::disk('public')->delete($filePathIcon);
                }
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not updated.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            if ($filePathActiveIcon) {
                Storage::disk('public')->delete($filePathActiveIcon);
            }
            if ($filePathIcon) {
                Storage::disk('public')->delete($filePathIcon);
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
            DB::beginTransaction();
            $data = ThemeApplication::find($request->id);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Check if the record exists
            if ($data->order_no == null) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Fill up Order # of Edit '.$data->name.' Application first .'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            $data->is_enable = ($data->is_enable == 1)?'0':'1';

            if ($data->save()) {
                $newDataArray = $data->toArray();
                $activity = 'Application = '.$data->id.' of Theme = '.$data->theme_id;
                $this->logHistoriesRepo->customUpdate($oldDataArray, $newDataArray, $activity);
                
                // Prepare the commands
                $commands = [
                    'get_theme'
                ];

                $themeData = Theme::find($data->theme_id);

                if($themeData->is_default === 0){
                    $conditions = [
                        [
                            'has' => 'room', // The `has` key indicates a `whereHas` condition
                            'relation' => 'room.themeRooms', // Name of the relation
                            'where' => [
                                ['field' => 'theme_id', 'operator' => '=', 'value' => $data->theme_id],
                            ],
                        ],
                    ];
                }
                else{
                    $conditions = [];
                }
                
                
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
}