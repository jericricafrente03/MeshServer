<?php

namespace App\Repositories\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Guests\IGuestBillingRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Models\General\Body\Guests\GuestBilling;
use App\Models\GeneralStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestBillingRepository extends Controller implements IGuestBillingRepository
{
    protected $logHistoriesRepo;
    protected $activityName;
    protected $helper;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'guest billing';
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
        $query = GuestBilling::query();

        $search = $request->search;
        $columns = $request->columns;

        if (!empty($search) && str_starts_with($search, '?')) {
            // Remove the '?' and search only by ID
            $searchId = ltrim($search, '?');
            $query->orWhere('guest_billings.id', 'like', '' . $searchId . '');
        } else {
            $query = $query->where(function($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    if ($column['searchable'] === "true") { 
                        $query->orWhere("guest_billings.{$column['name']}", 'like', '%' . $search . '%'); 
                    }
                }
            });
        }        

        $orderByCol = $columns[$orderColumnIndex]['name'];
        $query->orderBy("guest_billings.$orderByCol", $orderBy);
            
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();

        $statuses = GeneralStatus::where('category', 'guest_billing_status')->get();
        
        $newData = [];
        foreach ($data as $value) {
            
            $currentStatus = $statuses->firstWhere('id', $value->status_id);

            if(auth()->user()->canany(['guest_billings.edit'])) {
                $statusList = '<div class="btn-group w-100" id="myDropdown">
                        <button type="button" style="color: '.$currentStatus->color.'; background-color: '.$currentStatus->bg_color.'; border-color: '.$currentStatus->bg_color.';" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            '.$currentStatus->name.' <span class="caret"></span>
                        </button>   
                        <ul class="dropdown-menu dt-dropdown-menu">';
                foreach ($statuses as $status) {
                    $statusList .= '<li><a data-id="'.$value->id.'" data-value="'.$status->name.'" data-status_id="'.$status->id.'" class="dropdown-item single-status-dropdown" href="javascript:void(0)"  class="me-1"
                        ><i class="fa-solid fa-caret-right" style="color: '.$status->bg_color.';"></i> '.$status->name.'</a></li>';
                }
                
                $statusList .= '</ul>
                    </div>';
            }
            else{
                $statusList = '';
            }
            
            $newData[] = [
                'checkbox' => '',
                'id' => $value->id,
                'transaction_datetime' => date('Y-m-d h:i:s a', strtotime($value->transaction_datetime)),
                'guest_name' => $value->guest_name,
                'refno' => $value->refno,
                'room_number' => $value->room_number,
                'category' => Str::upper($value->category),
                'item_name' => $value->item_name,
                'quantity' => $value->quantity,
                'unit_price' => $value->unit_price,
                'user' => $value->user,
                'status' =>  $statusList
            ];
        }   
        
        return ["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData];
    
    }

    public function update($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();

            if($post['type'] == 'single'){
                $data = GuestBilling::find($post['id']);

                if (!$data) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Record not found.'
                    ], 404);
                }

                // Replicate old data for logging
                $oldDataArray = $data->replicate()->toArray();
                
                $data->status = $post['status'];
                $data->status_id = $post['status_id'];

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
            }
            else{
                $errors = []; // Array to store any errors
                $success = true; // Flag to check overall success
                $ids = $post['ids'];
                foreach($ids as $id){
                
                    // $data = GuestBilling::where('id', $id)->first();
                    // $oldData = GuestBilling::where('id', $id)->first();
                    $data = GuestBilling::find($id);

                    if (!$data) {
                        $errors[] = "Record with ID: $id not found.";
                        $success = false;
                        continue; // Skip to the next iteration
                    }

                    // Replicate old data for logging
                    $oldDataArray = $data->replicate()->toArray();
                    
                    $data->status = $post['status'];
                    $data->status_id = $post['status_id'];

                    if(!$data->save()){
                        $errors[] = "Failed to update record with ID: $id";
                        $success = false;
                    }
                    else{
                        // $oldDataArray = $oldData->toArray();
                        // $newDataArray = $data->toArray();

                        // $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
                        // Log the changes
                        $newDataArray = $data->toArray();
                        $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
                    }
                }
                
                if ($success) {
                    DB::commit();
                    // Record saved successfully
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

    public function updatePayment($request)
    {   
        try {
            DB::beginTransaction();
            $post = $request->all();

            if($post['type'] == 'single'){
                $data = GuestBilling::find($post['id']);

                if (!$data) {
                    return response()->json([
                        'status' => 'warning',
                        'message' => 'Record not found.'
                    ], 404);
                }

                // Replicate old data for logging
                $oldDataArray = $data->replicate()->toArray();
                
                $data->is_paid = $post['is_paid'];

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
            }
            else{
                $errors = []; // Array to store any errors
                $success = true; // Flag to check overall success
                $ids = $post['ids'];
                foreach($ids as $id){
                
                    // $data = GuestBilling::where('id', $id)->first();
                    // $oldData = GuestBilling::where('id', $id)->first();
                    $data = GuestBilling::find($id);

                    if (!$data) {
                        $errors[] = "Record with ID: $id not found.";
                        $success = false;
                        continue; // Skip to the next iteration
                    }

                    // Replicate old data for logging
                    $oldDataArray = $data->replicate()->toArray();
                    
                    $data->is_paid = $post['is_paid'];

                    if(!$data->save()){
                        $errors[] = "Failed to update record with ID: $id";
                        $success = false;
                    }
                    else{
                        // $oldDataArray = $oldData->toArray();
                        // $newDataArray = $data->toArray();

                        // $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
                        // Log the changes
                        $newDataArray = $data->toArray();
                        $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);
                    }
                }
                
                if ($success) {
                    DB::commit();
                    // Record saved successfully
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
}