<?php

namespace App\Repositories\SystemSettings\DeviceAdb;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\DeviceAdb\IApkRepository;
use App\Models\SystemSettings\DeviceAdb\AdbApk;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApkRepository extends Controller implements IApkRepository
{
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'device adb apk';
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
        $query = AdbApk::query();

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("adb_apks.{$column['name']}", 'like', '%' . $search . '%');  
                }  
            }   
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
       
        // Otherwise, order by columns in the devices table
        $query = $query->orderBy("adb_apks.$orderByCol", $orderBy); // Prefix with table name
    
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            if(auth()->user()->canany(['device_adb_apk.delete'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('device_adb_apk.delete')) {
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
                'created_at' => date('Y-m-d h:i', strtotime($value->created_at)),
                'actions' =>  $actions
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function delete($request)
    {   
        try {
            DB::beginTransaction();
            $input = $request->all();

            $id = $input['id'];
            
            $data = AdbApk::findOrFail($id);
            
            if ($data) {
                // Remove file
                if($data->uri != ''){
                    $uriPath = str_replace('storage/', '', $data->uri); 
                    $this->deleteFile($uriPath);
                }
                
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

    public function store($request)
    {   
        try {
            DB::beginTransaction();
            $filePath = '';

            // Specify a custom directory
            $customDirectory = 'upload/apk';
            // Check if video_uri exists in the request
            if ($request->hasFile('uri')) {
                // Use the trait to upload the file to the custom directory
                $filePath = $this->keepFilenameUploadFile($request->file('uri'), $customDirectory);
            }

            $file = $request->file('uri');
            $fileName = $file->getClientOriginalName(); 
    
            $data = new AdbApk();
            $data->name = $fileName;
            
            if($filePath){
                $relativeUrl = 'storage/' . $filePath;
                $data->uri = $relativeUrl;
            }

            if ($data->save()) {
                // Record saved successfully
                $dataArray = $data->toArray();

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
}