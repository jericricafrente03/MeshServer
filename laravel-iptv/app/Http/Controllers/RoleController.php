<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Interfaces\IUserHistoryLogRepository;
// use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{   
    protected $logHistoriesRepo;
    protected $activityName;
    /**
     * Instantiate a new RoleController instance.
     */
    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {   
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'role';
        $this->middleware('permission:role.index|role.create|role.update|role.delete');
    }

    public function index()
    {
        $user = Auth::user();
        $breadCrumb = ['System Users', 'Role'];
        return view('/systemUsers/roles/index', compact('user', 'breadCrumb'));
    }

    // public function create()
    // {
    //     $user = Auth::user();

    //     $viewPath = '/cs/roles/create_form';
    
    //     return view($viewPath, compact('user'));
    // }

    public function add_role(StoreRoleRequest $request)
    {
        if($request->ajax()){
            try{
                DB::beginTransaction();
                try {
                    $permissions = $request->permissions;
                    
                    $data = $request->validated();
                    unset($data["permissions"]);

                    $data['guard_name'] = 'web';
                    $data['name'] = str_replace(" ", "-", strtolower(trim($request->display_name)));
                    $data['level'] = $request->level;
                    $data['description'] = $request->description;
                    $role = Role::create($data);
                    $role->givePermissionTo($permissions);

                    // Convert the role to an array
                    $dataArray = $role->toArray();

                    // Add the permissions to the array
                    $dataArray['permissions'] = $permissions;
                    unset($dataArray['updated_at']);
                    unset($dataArray['created_at']);

                    $this->logHistoriesRepo->store($dataArray, $this->activityName);

                    DB::commit();

                    return json_encode([
                        'data'=> $role,
                        'status'=>'success',
                        'message'=>'Record has been saved.'
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack(); 
                    return response()->json([
                        'error' => $e->getMessage(),
                        'message' => 'Something went wrong in RoleController.add_role.db_transaction.'
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in RoleController.add_role.'
                ]);
            }
        }
    }

    public function update_role(UpdateRoleRequest $request)
    {
        if($request->ajax()){

            try {
                DB::beginTransaction();
                try {
                    $permissions = $request->permissions;

                    $data = $request->validated();
                    unset($data["permissions"]);

                    $req = $request->all();
                    
                    $role = Role::where('id', $req['id'])->first();
                    $oldData = Role::where('id', $req['id'])->first();
                    $oldPermissions = DB::table('role_has_permissions')
                        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                        ->where('role_has_permissions.role_id', $req['id'])
                        ->select('permissions.name')
                        ->get()
                        ->pluck('name')
                        ->toArray();
                    $role->update([
                        'display_name'  =>  $req['display_name'], 
                        'description'   =>  $req['description'],
                        'level' => $req['level'],
                        'name' => str_replace(" ", "-", strtolower(trim($req['display_name'])))
                    ]);

                    $newData = Role::where('id', $req['id'])->first();
                    // $role->givePermissionTo($permissions);
                    $role->syncPermissions($permissions);
                    
                    $oldDataArray = $oldData->toArray();
                    $newDataArray = $newData->toArray();
                    
                    $oldDataArray['permissions'] = $oldPermissions;
                    $newDataArray['permissions'] = $permissions;

                    $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                    DB::commit();
                    return json_encode([
                        'data'=> $role,
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
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in RoleController.update_role.'
                ]);
            }
        }
    } 

    public function delete_role(Request $request)
    {
        if($request->ajax()){
            
            try {
                
                $input = $request->all();
                $result = User::where('role_id', $input['id']);
                
                if($result->count() > 0){
                        return json_encode([
                        'data'=>'Role '.$input['id'],
                        'status'=>'error',
                        'message'=>'Referential error.'
                    ]);
                }
                
                $role = Role::find($input['id']);
                $role->syncPermissions([]);
                $role->delete();

                $this->logHistoriesRepo->delete($input['id'], $this->activityName);

                return json_encode([
                    'data'=>$role,
                    'status'=>'success',
                    'message'=>'Record has been deleted.'
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in UserController.add_role.'
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

            // get data from products table
            $query = new Role();

            // Search //input all searchable fields
            $search = $request->search;
            $query = $query->where(function($query) use ($search){
                $query->orWhere('display_name', 'like', "%".$search."%");   
            });

            //default field for order
            $orderByCol = 'id';
        
            //input all orderable fields
            switch($orderColumnIndex){
                case '0':
                    $orderByCol = 'id';
                    break;
                case '1':
                    $orderByCol = 'display_name';
                    break;
            }

            $query = $query->orderBy($orderByCol, $orderBy);
            $recordsFiltered = $recordsTotal = $query->count();
            $data = $query->skip($skip)->take($pageLength)->get();

            $newData = [];
            foreach ($data as $value) {
                if(auth::user()->role->level < $value->level){
                    $actions = '<div class="d-flex order-actions">';
                    if(Auth::user()->can('role.update') && $value->name != 'super-admin') {
                        $actions .= '<a data-bs-toggle="modal" data-bs-target="#editRole_modal"
                        data-name="'.$value->name.'" 
                        data-display_name="'.$value->display_name.'" data-description="'.$value->description.'" 
                        data-id="'.$value->id.'"
                        data-level="'.$value->level.'"
                        class="btn-primary" style="background-color:#8833ff"><i class="bx bxs-edit"></i></a>';
                    }
                    if(Auth::user()->can('role.delete') && $value->name != 'super-admin') {
                        $actions .= '<a onclick="ShowConfirmDeleteForm(' . $value->id . ')" class="btn-danger ms-3" style="background-color:#dc362e"><i class="bx bxs-trash"></i></a>';
                    }
                    $actions .= '</div>';
                    
                    $newData[] = [
                        'id' => $value->id,
                        'name' => $value->name,
                        'display_name' => $value->display_name,
                        'description' => $value->description,
                        'actions' =>  $actions
                    ];
                }
            }   
            
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData], 200);
        }

    }
}
