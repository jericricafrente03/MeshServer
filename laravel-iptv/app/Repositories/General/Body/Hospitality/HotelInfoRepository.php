<?php

namespace App\Repositories\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Hospitality\IHotelInfoRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Jobs\SendDataToDevicesJob;
use App\Models\General\Body\Hospitality\HotelInfo;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HotelInfoRepository extends Controller implements IHotelInfoRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'hotel info';
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
        $query = HotelInfo::query();

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("hotel_infos.{$column['name']}", 'like', '%' . $search . '%');   
                }  
            }
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        // Apply ordering
        $query->orderBy("hotel_infos.$orderByCol", $orderBy);
        
        
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

            if(auth()->user()->canany(['hotel_infos.view','hotel_infos.update', 'hotel_infos.delete', 'hotel_infos.enable', 'hotel_infos.change_order'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('hotel_infos.change_order')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" class="me-1"
                        onclick="showChangeOrderModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-arrows-v text-primary me-1 ms-1"></i> Change Order</a></li>';
                }
                if(auth()->user()->can('hotel_infos.enable')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="toggleEnable('.$value->id.');"><i class="fa '.$toggleIcon.'"></i> '.$toggleName.'</a></li>';
                }
                if(auth()->user()->can('hotel_infos.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('hotel_infos.edit')) {
                    // $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="showEditModal()"><i class="fa fa-edit"></i> Edit</a></li>';
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('hotel_infos.delete')) {
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
                'order_no' => $value->order_no,
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
            $customDirectory = 'upload/hotel_infos';
            // Check if img_uri exists in the request
            if ($request->hasFile('img_uri')) {
                // Use the trait to upload the file to the custom directory
                $filePath = $this->uploadFileWithThumbnail($request->file('img_uri'), $customDirectory);
            }
    
            $data = new HotelInfo();
            $data->name = $post['name'];
            $data->description = $post['description'];
            $data->order_no = $post['order_no'];
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
                    'get_hotel_infos'
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
            $data = HotelInfo::find($request->id);

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
                    'get_hotel_infos'
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

            // Retrieve the existing record
            $data = HotelInfo::find($post['id']);

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
            $customDirectory = 'upload/hotel_infos';
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
            $data->order_no = $post['order_no'];

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
                    'get_hotel_infos'
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
            
            $data = HotelInfo::where('id', $id)->first();
            
            if ($data) {
                // Remove file
                if($data->img_uri != ''){
                    $path = str_replace('storage/', '', $data->img_uri); 
                    $this->deleteFile($path);
                    $thumbnailPath = str_replace('storage/', '', $data->img_thumbnail_uri); 
                    $this->deleteFile($thumbnailPath);
                }
                $data->delete();

                $this->logHistoriesRepo->delete($id, $this->activityName);
                // Prepare the commands
                $commands = [
                    'get_hotel_infos'
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

    public function changeOrder($request)
    {
        try {
            DB::beginTransaction();
            $post = $request->all();
            
            // Retrieve the existing record
            $data = HotelInfo::find($post['id']);

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

                $commands = [
                    'get_hotel_infos'
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