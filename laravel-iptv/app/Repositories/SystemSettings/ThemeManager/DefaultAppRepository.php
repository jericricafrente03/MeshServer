<?php

namespace App\Repositories\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\ThemeManager\IDefaultAppRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\SystemSettings\ThemeManager\DefaultApp;
use App\Models\SystemSettings\ThemeManager\Theme;
use App\Models\SystemSettings\ThemeManager\ThemeApplication;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DefaultAppRepository extends Controller implements IDefaultAppRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'default apps';
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
        $query = DefaultApp::query();

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("default_apps.{$column['name']}", 'like', '%' . $search . '%');
                }
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        $query->orderBy("default_apps.$orderByCol", $orderBy);
        
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
            
            if(auth()->user()->canany(['default_apps.edit','default_apps.enable', 'default_apps.delete'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('default_apps.enable')) {
                    $actions .= '<button title="'.$enableTitle.'" class="btn '.$enableClass.' btn-sm me-2 fixed-size-btn" 
                            onclick="toggleEnable('.$value->id.', '.$value->is_enable.');">
                            <i class="'.$enableIcon.'"></i>
                        </button>';
                }

                if(auth()->user()->can('default_apps.edit')) {
                    $actions .= '<button title="Edit Default Application" class="btn btn-info btn-sm me-2 fixed-size-btn" 
                            onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-gear"></i>
                        </button>';
                }

                if(auth()->user()->can('default_apps.delete')) {
                    $actions .= '<button title="Delete Default Application" class="btn btn-danger btn-sm me-2 fixed-size-btn" 
                            onclick="ShowConfirmDeleteForm('.$value->id.');">
                            <i class="fa-regular fa-trash-can"></i>
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

    public function toggleEnable($request)
    {
        try{
            DB::beginTransaction();
            $data = DefaultApp::find($request->id);

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
                ThemeApplication::where('application_id', $data->id)->update(['is_enable' => 0]);
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // ThemeApplication::where('application_id', $request->id)
                //     ->update(['is_enable' => $data->is_enable]);

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

    public function store($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $themeApplicationData = [];
            $now = now();
    
            $data = new DefaultApp();
            $data->name = $post['name'];
            $data->method = str_replace(' ', '_', strtolower($post['method']));
            $data->is_enable = 0;
            $data->color = $post['color'];

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                $themeCount = Theme::count();
                
                if($themeCount > 0){
                    $themes = Theme::all();
                    
                    foreach ($themes as $theme) {
                        $themeApplicationData[$theme->id] = [
                            'icon' => null,
                            'active_icon' => null,
                            'text_color' => '#33333f',
                            'active_text_color' => '#bbbbbb',
                            'is_enable' => 0,
                            'order_no' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    // dd($themeApplicationData);
                    // Attach the zone to all themes at once
                    $data->themes()->attach($themeApplicationData);

                    $commands = [
                        'get_theme'
                    ];
                    
                    $conditions = [];

                    // Queue the job to be executed after commit
                    DB::afterCommit(function () use ($commands, $conditions) {
                        // Dispatch the job in the background
                        SendDataToDevicesJob::dispatch($commands, $conditions);
                    });
                }

                $commands = [
                    'get_theme'
                ];
                
                $conditions = [];

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

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $filePathIcon = '';
            $filePathActiveIcon = '';
            
            // Retrieve the existing record
            $data = DefaultApp::find($post['id']);

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
            $data->method = $post['method'];
            $data->color = $post['color'];

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

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
            
            $data = DefaultApp::where('id', $id)->first();
            
            if ($data) {
                
                // Collect all bg_uri values (excluding null) before deleting the zone
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
}