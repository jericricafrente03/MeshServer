<?php

namespace App\Repositories\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Interfaces\General\Body\Guests\IGuestRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Models\General\Body\Guests\Guest;
use App\Models\General\Body\Guests\RoomAssignment;
use Illuminate\Support\Facades\DB;

class GuestRepository extends Controller implements IGuestRepository
{
    protected $logHistoriesRepo;
    protected $activityName;
    protected $helper;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'guest';
        $this->helper = new Helper;
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
        $query = Guest::with('country');

        $search = $request->search;
        $columns = $request->columns;
        $query = $query->where(function($query) use ($search, $columns){
            foreach ($columns as $column) {
                if($column['searchable'] === "true"){ 
                    if($column['name'] !== 'country') {
                        if ($column['name'] === 'name') {
                            $query->orWhereRaw("CONCAT(guests.title, ' ', guests.firstname, ' ', guests.lastname) LIKE ?", ['%' . $search . '%']);
                        } else {
                            $query->orWhere("guests.{$column['name']}", 'like', '%' . $search . '%'); 
                        }
                    }
                }  
            }
            $query->orWhereHas('country', function ($query) use ($search) {
                $query->where("countries.country_name", "like", '%' . $search . '%');
            });
        });

        $orderByCol = $columns[$orderColumnIndex]['name'];
        if ($orderByCol === 'country') {
            $query->join('countries', 'guests.country_id', '=', 'countries.id')
                ->select('guests.*', 'countries.country_name')
                ->distinct()
                ->orderBy('countries.country_name', $orderBy);
        } else {
            if ($orderByCol === 'name') {
                $query->orderByRaw("CONCAT(guests.firstname, ' ', guests.lastname) $orderBy");
            } else {
                // Ensure the column exists and is valid
                if (in_array($orderByCol, ['id', 'firstname', 'lastname', 'title'])) {
                    $query->orderBy("guests.$orderByCol", $orderBy);
                }
            }
        }
        
        $recordsFiltered = $recordsTotal = $query->count();
        $data = $query->skip($skip)->take($pageLength)->get();
        
        $newData = [];
        foreach ($data as $value) {
            $name = ($value->title)?$value->title .' '. $value->firstname .' '. $value->lastname:$value->firstname .' '. $value->lastname;
            
            if(auth()->user()->canany(['guests.view','guests.update', 'guests.delete', 'guests.create'])) {
                $actions = '<div class="btn-group" id="myDropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dt-dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>   
                        <ul class="dropdown-menu dt-dropdown-menu">';
                if(auth()->user()->can('guests.view')) {
                    $actions .= '<li><a class="dropdown-item" href="javascript:void(0)"  class="me-1"
                        onclick="showViewModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-eye text-secondary"></i> View</a></li>';
                }
                if(auth()->user()->can('guests.edit')) {
                    // $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="showEditModal()"><i class="fa fa-edit"></i> Edit</a></li>';
                    $actions .= '<li><a class="dropdown-item" title="Edit" href="javascript:void(0)" data-id="'.$value->id.'" class="me-1"
                                id="data-edit-btn-'.$value->id.'" onclick="showEditModal('.$value->id.', '.htmlspecialchars(json_encode($value)).');"><i class="fa fa-edit text-info"></i> Edit</a></li>';
                }
                if(auth()->user()->can('guests.delete')) {
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
                'id' => $value->id,
                'name' => $name,
                'city' => $value->city,
                'country' => $value->country->country_name,
                'mobile_no' => $value->mobile_no,
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
            
            $data = new Guest();
            $data->title = $this->helper->ProperNamingCase($post['title']);
            $data->firstname = $this->helper->ProperNamingCase($post['firstname']);
            $data->lastname = $this->helper->ProperNamingCase($post['lastname']);
            $data->birthdate = $post['birthdate'];
            $data->landline_no = $post['landline_no'];
            $data->mobile_no = $post['mobile_no'];
            $data->email = $post['email'];
            $data->street1 = $post['street1'];
            $data->street2 = $post['street2'];
            $data->city = $post['city'];
            $data->state_region = $post['state_region'];
            $data->country_id = $post['country_id'];
            $data->zip_code = $post['zip_code'];

            $data->save();

            // Convert the role to an array
            $dataArray = $data->toArray();

            // Remove to the array
            unset($dataArray['updated_at']);
            unset($dataArray['created_at']);

            $this->logHistoriesRepo->store($dataArray, $this->activityName);

            DB::commit();

            if ($data->save()) {
                // Record saved successfully
                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been saved.'
                ], 201);
            } else {
                // Failed to save the record
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
            $data = Guest::find($post['id']);

            // Check if the record exists
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Guest not found.'
                ], 404);
            }

            // Store old data for logging purposes
            $oldDataArray = $data->toArray();

            // $data = Guest::where('id', $post['id'])->first();
            // $oldData = Guest::where('id', $post['id'])->first();
            $data->title = $this->helper->ProperNamingCase($post['title']);
            $data->firstname = $this->helper->ProperNamingCase($post['firstname']);
            $data->lastname = $this->helper->ProperNamingCase($post['lastname']);
            $data->birthdate = $post['birthdate'];
            $data->landline_no = $post['landline_no'];
            $data->mobile_no = $post['mobile_no'];
            $data->email = $post['email'];
            $data->street1 = $post['street1'];
            $data->street2 = $post['street2'];
            $data->city = $post['city'];
            $data->state_region = $post['state_region'];
            $data->country_id = $post['country_id'];
            $data->zip_code = $post['zip_code'];

            // $oldDataArray = $oldData->toArray();


            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                // Check for room_assignments with customer_id = $data->id and check_is = 0
                $roomAssignments = RoomAssignment::where('customer_id', $data->id)
                    ->where('is_checkout', 0)
                    ->get();

                foreach ($roomAssignments as $assignment) {
                    $assignment->customer_name = trim($data->title . ' ' . $data->firstname . ' ' . $data->lastname);
                    $assignment->save();
                }

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

            // Check if the guest is currently checked in
            $isCheckedIn = RoomAssignment::where('customer_id', $id)
                ->where('is_checkout', 0)
                ->exists(); // Use exists() for a boolean result
           
            if ($isCheckedIn) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Cannot delete the guest. The guest is currently checked in.'
                ], 400); // Use 400 Bad Request for this scenario
            }
            
            $data = Guest::where('id', $id)->first();
            

            if ($data) {
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
                ], 404); // 404 status code for "Not Found"
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