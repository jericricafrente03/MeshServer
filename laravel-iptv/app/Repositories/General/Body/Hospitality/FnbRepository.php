<?php

namespace App\Repositories\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Hospitality\IFnbRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Hospitality\Fnb;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FnbRepository extends Controller implements IFnbRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'fnb';
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
        $query = Fnb::with('category')->whereNull('deleted_at');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    if($column['name'] !== 'category') {
                        $query->orWhere("fnbs.{$column['name']}", 'like', '%' . $search . '%');
                    }
                }  
            }
            $query->orWhereHas('category', function ($query) use ($search) {
                $query->where("fnb_categories.name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        if ($orderByCol === 'category') {
            $query->join('fnb_categories', 'fnbs.category_id', '=', 'fnb_categories.id')
                ->select('fnbs.*', 'fnb_categories.name as category_name')
                ->distinct()
                ->orderBy('fnb_categories.name', $orderBy);
        }else {
            $query->orderBy("fnbs.$orderByCol", $orderBy);
        }

        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
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

            if(auth()->user()->canany(['fnb.view','fnb.update', 'fnb.delete', 'fnb.enable'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('fnb.enable')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="toggleEnable('.$value->id.');"><i class="fa '.$toggleIcon.'"></i> '.$toggleName.'</a></li>';
                }
                if(auth()->user()->can('fnb.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('fnb.edit')) {
                    // $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="showEditModal()"><i class="fa fa-edit"></i> Edit</a></li>';
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('fnb.delete')) {
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

            if($value->img_uri){
                $img_uri = '<a href="' . asset($value->img_uri) . '" target="_blank"><img src="' . asset($value->img_thumbnail_uri) . '" alt="Image" style="width:50px; height:50px; object-fit:cover;" class="img img-thumbnail"></a>';
            }
            else{
                $img_uri = '<a href="' . asset('upload/no_image.jpg') . '" target="_blank"><img src="' . asset('upload/no_image.jpg') . '" alt="Image" style="width:50px; height:50px; object-fit:cover;" class="img img-thumbnail"></a>';
            }
            
            $newData[] = [
                'id' => $value->id,
                'img_uri' => $img_uri,
                'name' => $value->name,
                'category' => $value->category->name,
                'unit_price' => $value->unit_price,
                'is_enable' => $is_enable,
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

            // Specify a custom directory
            $customDirectory = 'upload/fnb';
            // Check if img_uri exists in the request
            if ($request->hasFile('img_uri')) {
                // Use the trait to upload the file to the custom directory
                $filePath = $this->uploadFileWithThumbnail($request->file('img_uri'), $customDirectory);
            }
    
            $data = new Fnb();
            $data->name = $post['name'];
            $data->description = $post['description'];
            $data->unit_price = $post['unit_price'];
            $data->category_id = $post['category_id'];
            $data->is_enable = 0;

            if($filePath){
                $relativeUrl = 'storage/' . $filePath['original'];
                $data->img_uri = $relativeUrl;
                $thumbnailRelativeUrl = 'storage/' . $filePath['thumbnail'];
                $data->img_thumbnail_uri = $thumbnailRelativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully

                // Convert the role to an array
                $dataArray = $data->toArray();

                // Remove to the array
                unset($dataArray['updated_at']);
                unset($dataArray['created_at']);

                $this->logHistoriesRepo->store($dataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_fnbs',
                    'get_fnb_categories'
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
                    'message'=>'Record has been saved.'
                ], 201);
            } else {
                // Failed to save the record

                DB::rollBack(); 
                if ($filePath) {
                    Storage::disk('public')->delete($filePath['original']);
                    Storage::disk('public')->delete($filePath['thumbnail']);
                }
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not saved.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            if ($filePath) {
                Storage::disk('public')->delete($filePath['original']);
                Storage::disk('public')->delete($filePath['thumbnail']);
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
            $fnb = fnb::find($request->id);

            // Check if the record exists
            if (!$fnb) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $fnb->toArray();

            $fnb->is_enable = ($fnb->is_enable == 1)?'0':'1';

            if ($fnb->save()) {
                $newDataArray = $fnb->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_fnbs',
                    'get_fnb_categories'
                ];


                // Closure to apply multiple conditions 
                // example of complex condition to pass in SendDataToDevicesJob arguement 2
                // $conditions = [
                //     ['field' => 'status', 'operator' => '=', 'value' => 'active'],
                //     ['or' => [
                //         ['field' => 'location', 'operator' => '=', 'value' => 'office'],
                //         ['field' => 'id', 'operator' => '=', 'value' => 2],
                //     ]],
                //     ['field' => 'type', 'operator' => '=', 'value' => 'premium']
                // ];
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

                $toggleMessage = ($fnb->is_enable == 1)?'on':'off';
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

            // Retrieve the existing record
            $data = Fnb::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            // Specify a custom directory
            $customDirectory = 'upload/fnb';
            // Check if img_uri exists in the request
            if ($request->hasFile('img_uri')) {
                // Remove file
                if($data->img_uri != ''){
                    $path = str_replace('storage/', '', $data->img_uri); 
                    $this->deleteFile($path);
                }
                if($data->img_thumbnail_uri != ''){
                    $thumbnailPath = str_replace('storage/', '', $data->img_thumbnail_uri); 
                    $this->deleteFile($thumbnailPath);
                }
                // Use the trait to upload the file to the custom directory
                $filePath = $this->uploadFileWithThumbnail($request->file('img_uri'), $customDirectory);
            }
            
            $data->name = $post['name'];
            $data->description = $post['description'];
            $data->unit_price = $post['unit_price'];
            $data->category_id = $post['category_id'];

            if($filePath){
                $relativeUrl = 'storage/' . $filePath['original'];
                $data->img_uri = $relativeUrl;
                $thumbnailRelativeUrl = 'storage/' . $filePath['thumbnail'];
                $data->img_thumbnail_uri = $thumbnailRelativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Prepare the commands
                $commands = [
                    'get_fnbs',
                    'get_fnb_categories'
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
                    'message'=>'Record has been updated.'
                ], 200);
            } else {
                // Failed to save the record
                DB::rollBack(); 
                if ($filePath) {
                    Storage::disk('public')->delete($filePath['original']);
                    Storage::disk('public')->delete($filePath['thumbnail']);
                }
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not updated.'
                ], 404);
            }
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            if ($filePath) {
                Storage::disk('public')->delete($filePath['original']);
                Storage::disk('public')->delete($filePath['thumbnail']);
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
            
            $data = Fnb::where('id', $id)->first();
            
            if ($data) {
                if (!empty($data->img_uri)) {
                    $path = str_replace('storage/', '', $data->img_uri); 
                    $this->deleteFile($path);
                }
                if (!empty($data->img_thumbnail_uri)) {
                    $thumbnailPath = str_replace('storage/', '', $data->img_thumbnail_uri); 
                    $this->deleteFile($thumbnailPath);
                }
                // Remove image references
                $data->img_uri = null;
                $data->img_thumbnail_uri = null;
                // $data->category_id = null;
                $data->save();

                $data->delete();

                $this->logHistoriesRepo->delete($id, $this->activityName);
                // Prepare the commands
                $commands = [
                    'get_fnbs',
                    'get_fnb_categories'
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