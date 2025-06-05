<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\IUserHistoryLogRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserHistoryLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSettingController extends Controller
{    
    protected $logHistoriesRepo;
    protected $activityName;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->middleware('permission:user.index|user.create|user.update|user.delete');
        $this->activityName = 'user';
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/systemUsers/users/index';    

        $breadCrumb = ['System Users', 'User'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function delete_user(Request $request)
    {
        if($request->ajax()){
            
            try {
                DB::beginTransaction();
                //code...
                $input = $request->all();
                $user = User::find($input['id']);
                $user->delete();

                $this->logHistoriesRepo->delete($input['id'], $this->activityName);

                DB::commit();
                return json_encode([
                    'data'=>$user->id,
                    'status'=>'success',
                    'message'=>'Record has been deleted.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in UserController.delete_user.db_transaction.'
                ]);
            }
        }
    }

    public function add_user(StoreUserRequest $request)
    {
        if($request->ajax()){
            try {
                DB::beginTransaction();
                //$data = $request->validated();
                $req = $request->all();
                $user = new User();
                // $user->create($data);
                $user->firstname = ucwords(strtolower($req['firstname']));
                $user->lastname = ucwords(strtolower($req['lastname']));
                
                $user->name = str_replace(" ", "", strtolower(trim($req['name'])));
                $user->email = $req['email'];
                $user->password = $req['password'];
                $user->role_id = $req['role_id'];
                $user->initials_random_color =  rand(1, 10);
                $user->save();

                // Prepare the data for logging, excluding updated_at
                $userDataForLog = $user->toArray();
                unset($userDataForLog['updated_at']);
                unset($userDataForLog['created_at']);
                
                $this->logHistoriesRepo->store($userDataForLog, $this->activityName);

                $role = Role::findOrFail($req['role_id']);
                $user->assignRole($role->name);
            
                DB::commit();

                return json_encode([
                    'data'=> $user,
                    'status'=>'success',
                    'message'=>'Record has been saved.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in UserController.add_user.db_transaction.'
                ]);
            }
        }
    }

    public function update_user(UpdateUserRequest $request)
    {
        if($request->ajax()){

            try {
                DB::beginTransaction();
                $input = $request->all();
                
                $user = User::where('id', $input['id'])->first();
                $oldData = User::where('id', $input['id'])->first();
                //$role->update($data);
                $user->firstname = ucwords(strtolower($input['firstname']));
                $user->lastname = ucwords(strtolower($input['lastname']));
                $user->name = $input['name'];
                $user->email = $input['email'];
                ($input['password'])?$user->password = $input['password']:'';
                $user->role_id = $input['role_id'];
                $user->save();

                $oldDataArray = $oldData->toArray();
                $newDataArray = $user->toArray();

                // If password was updated, replace it with 'new entry'
                if (!empty($input['password'])) {
                    $oldDataArray['password'] = 'old entry';
                    $newDataArray['password'] = 'new entry';
                }

                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                $role = Role::findOrFail($input['role_id']);

                $user->roles()->detach();
                $user->assignRole($role->name);

                DB::commit();
                return json_encode([
                    'data'=> $user,
                    'status'=>'success',
                    'message'=>'Record has been updated.'
                ]);

            } catch (\Exception $e) {
                DB::rollBack(); 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in RoleController.update_role.db_transaction.'
                ]);
            }
        }
    } 

    public function get_data(Request $request)
    {   
        if($request->ajax()){
            // Page Length
            $pageNumber = ( $request->start / $request->length )+1;
            $pageLength = $request->length;
            $skip       = ($pageNumber-1) * $pageLength;

            // Page Order
            $orderColumnIndex = $request->order[0]['column'] ?? '0';
            $orderBy = $request->order[0]['dir'] ?? 'desc';
            
                
            $query = User::select('users.*', 'roles.display_name AS role', 'roles.level AS level')
                ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('users.role_id', '!=', 3 );
            // Search //input all searchable fields
            $search = $request->search;
            $columns = $request->columns;
            $query = $query->where(function($query) use ($search, $columns){
                foreach ($columns as $column) {
                    if($column['searchable'] === "true"){
                        $query->orWhere("$column[name]", 'like', "%".$search."%");
                    }  
                }   
            });
            
            $orderByCol = $request->columns[$request->order[0]['column']]['name'];
            
            $query = $query->orderBy($orderByCol, $orderBy);
            $recordsFiltered = $recordsTotal = $query->count();
            $data = $query->skip($skip)->take($pageLength)->get();

            
            $newData = [];
            foreach ($data as $value) {
                if(auth::user()->role->level < $value->level){

                    $canEdit = Auth::user()->can('user.update') ? true : false;

                    $fullname = $value->firstname.' '.$value->lastname;
                    if($canEdit === true) {
                        $fullname = '<span class="clickable-text" onClick="clickUserName('.$value->id.')">'.$fullname.'</span>';
                    }

                    if(!empty($value->image)) {
                        $avatar = '
                            <div class="d-flex">
                                <img src="/upload/userprofile/'.$value->image.'" width="35" height="35" class="rounded-circle" alt="">
                                <div class="flex-grow-1 ms-3 mt-2">
                                    <p class="font-weight-bold mb-0">'.$fullname.'</p>
                                </div>
                            </div>
                        ';
                    } else {
                        $avatar = '
                            <div class="d-flex">
                                <div class="employee-avatar-'.$value->initials_random_color.'-initials hr-employee" data-id="'.$value->id.'">
                                '.strtoupper(substr($value->firstname, 0, 1)).strtoupper(substr($value->lastname, 0, 1)).'
                                </div>
                                <p class="font-weight-bold mb-0 ms-3 mt-2">'.$fullname.'</p>
                            </div>
                        ';
                    }

                    $actions = '<div class="d-flex order-actions">';
                    if(Auth::user()->can('user.update')) {
                        $actions .= '<button class="btn btn-primary btn-sm me-2" id="user_edit_btn_'.$value->id.'"
                            data-name="'.$value->name.'" data-email="'.$value->email.'" 
                            data-id="'.$value->id.'" data-roleid="'.$value->role_id.'"
                            data-firstname="'.$value->firstname.'" data-lastname="'.$value->lastname.'"
                            onclick="showEditForm(this);">
                            <i class="fa fa-pencil"></i>
                        </button>';
                    }
                    if(Auth::user()->can('user.delete')) {
                        $actions .= ' <button class="btn btn-danger btn-sm" 
                            onclick="ShowConfirmDeleteForm(' . $value->id . ')">
                            <i class="fa fa-trash-can"></i>
                        </button>';
                    }
                    $actions .= '</div>';
                    $newData[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'email' => $value->email,
                        'role' => $value->role,
                        'avatar' => $avatar,
                        'actions' => $actions
                    ];
                }
            }   
            
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData], 200);
        }

    }

    public function log_index()
    {
        $user = Auth::user();

        $viewPath = '/systemUsers/logs/index';    

        $breadCrumb = ['System Users', 'Log'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function get_logData(Request $request)
    {   
        if($request->ajax()){
            // Page Length
            $pageNumber = ( $request->start / $request->length )+1;
            $pageLength = $request->length;
            $skip       = ($pageNumber-1) * $pageLength;

            // Page Order
            $orderColumnIndex = $request->order[0]['column'] ?? '0';
            $orderBy = $request->order[0]['dir'] ?? 'desc';
            
                
            $query = new UserHistoryLog();

            // Search //input all searchable fields
            $search = $request->search;
            $columns = $request->columns;

            $query = $query->where(function($query) use ($search, $columns){
                foreach ($columns as $column) {
                    if($column['searchable'] === "true"){
                        $query->orWhere("$column[name]", 'like', "%".$search."%");
                    }  
                }   
            });
            
            $orderByCol =  $columns[$orderColumnIndex]['name'];
            
            $query = $query->orderBy($orderByCol, $orderBy);
            $recordsFiltered = $recordsTotal = $query->count();
            $data = $query->skip($skip)->take($pageLength)->get();

            
            $newData = [];
            foreach ($data as $value) {
                

                $actions = '<div class="d-flex order-actions">';
                if(Auth::user()->can('log.show')) {
                    $actions .= '<button class="btn btn-secondary btn-sm me-2" id="user_edit_btn_'.$value->id.'"
                        data-description="'.htmlspecialchars($value->description).'"
                        onclick="showForm(this);">
                        <i class="fa fa-eye"></i>
                    </button>';
                }
                $actions .= '</div>';
                $newData[] = [
                    'id' => $value->id,
                    'activity' => $value->activity,
                    'username' => $value->username,
                    'ip_address' => $value->ip_address,
                    'created_at' => date('Y-m-d h:i:s a', strtotime($value['created_at'])),
                    'actions' => $actions
                ];
            
            }   
            
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData], 200);
        }

    }

    public function get_roles(Request $request)
    {
        if($request->ajax()){
            $data = Role::get();

            return json_encode([
                'data'=> $data,
            ]);
        }

    }

}


