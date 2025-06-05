<?php

namespace App\Repositories\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\ThemeManager\IZoneRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\SystemSettings\ThemeManager\Theme;
use App\Models\SystemSettings\ThemeManager\Zone;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;

class ZoneRepository extends Controller implements IZoneRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'zones';
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
        $query = Zone::query();

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("zones.{$column['name']}", 'like', '%' . $search . '%');
                }
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        $query->orderBy("zones.$orderByCol", $orderBy);
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            
            if(auth()->user()->canany(['zones.edit', 'zones.delete'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('zones.edit')) {
                    $actions .= '<button title="Edit Zone" class="btn btn-info btn-sm me-2 fixed-size-btn" 
                            onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');">
                            <i class="fa-solid fa-gear"></i>
                        </button>';
                }

                if(auth()->user()->can('zones.delete')) {
                    $actions .= '<button title="Delete Zone" class="btn btn-danger btn-sm me-2 fixed-size-btn" 
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

    public function store($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();
            $themeZoneData = [];
            $now = now();
    
            $data = new Zone();
            $data->name = $post['name'];

            $themeCount = Theme::count();

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                if($themeCount > 0){
                    $themes = Theme::all();

                    foreach ($themes as $theme) {
                        $themeZoneData[$theme->id] = [
                            'bg_uri' => null,
                            'text_color' => '#33333f',
                            'active_text_color' => '#bbbbbb',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    // Attach the zone to all themes at once
                    $data->themes()->attach($themeZoneData);

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
            
            // Retrieve the existing record
            $data = Zone::find($post['id']);

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

    public function delete($request)
    {   
        try {
            DB::beginTransaction();
            $input = $request->all();

            $id = $input['id'];
           
            $data = Zone::where('id', $id)->first();
            
            if ($data) {
                // Collect all bg_uri values (excluding null) before deleting the zone
                $bgUris = $data->theme_zones()
                    ->whereNotNull('bg_uri') // Exclude null values
                    ->pluck('bg_uri')
                    ->toArray();
                
                $data->delete();

                // Loop through the bg_uri array and delete the files
                foreach ($bgUris as $bgUri) {
                    $tzBgUri = str_replace('storage/', '', $bgUri); 
                    $this->deleteFile($tzBgUri);
                }  

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