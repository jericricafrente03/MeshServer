<?php

namespace App\Repositories\SystemSettings\DeviceAdb\Files;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\DeviceAdb\Files\IScreenCaptureRepository;
use App\Models\SystemSettings\DeviceAdb\AdbFile;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;

class ScreenCaptureRepository extends Controller implements IScreenCaptureRepository
{   
    use FileUploadTrait;

    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'device adb files screen capture';
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
        $query = AdbFile::where('type', 'image');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){
                    $query->orWhere("adb_files.{$column['name']}", 'like', '%' . $search . '%');  
                }  
            }   
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
       
        // Otherwise, order by columns in the devices table
        $query = $query->orderBy("adb_files.$orderByCol", $orderBy); // Prefix with table name
    
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            if(auth()->user()->canany(['device_adb_screen_capture.delete'])) {
                $actions = '<div class="d-flex order-actions">';

                if(auth()->user()->can('device_adb_screen_capture.delete')) {
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

            if($value->img_uri){
                $img_uri = '<a href="' . asset($value->img_uri) . '" target="_blank"><img src="' . asset($value->img_uri) . '" alt="Image" style="width:50px; height:50px; object-fit:cover;" class="img img-thumbnail"></a>';
            }
            else{
                $img_uri = '<a href="' . asset('upload/no_image.jpg') . '" target="_blank"><img src="' . asset('upload/no_image.jpg') . '" alt="Image" style="width:50px; height:50px; object-fit:cover;" class="img img-thumbnail"></a>';
            }
            
            $newData[] = [
                'id' => $value->id,
                'img_uri' => $img_uri,
                'mac_address' => $value->mac_address,
                'ip4_address' => $value->ip4_address,
                'room' => $value->room,
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
            
            $data = AdbFile::findOrFail($id);
            
            if ($data) {
                // Remove file
                if($data->img_uri != ''){
                    $imgPath = str_replace('storage/', '', $data->img_uri); 
                    $this->deleteFile($imgPath);
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

    public function checkTableChanges($request)
    {
        $latestUpdate = AdbFile::where('type', 'image')->max('updated_at'); // Get latest update timestamp
        return response()->json(['latest_update' => $latestUpdate]);
    }
}