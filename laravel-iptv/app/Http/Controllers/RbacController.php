<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserHistoryLogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RbacController extends Controller
{   
    protected $logHistoriesRepo;
    protected $activityName;
    /**
     * Instantiate a new RbacController instance.
     */
    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {   
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'role';
        $this->middleware('permission:rbac.index|rbac.create|rbac.update|rbac.delete');
    }

    public function index()
    {
        $permissionGroups = Permission::orderBy('group_name', 'asc')->pluck('display_name','group_name');
        $user = Auth::user();
        $breadCrumb = ['System Users', 'Role-based Access Control'];
        return view('/systemUsers/rbac/index', compact('user', 'breadCrumb', 'permissionGroups'));
    }

    public function get_roles(Request $request)
    {
        $data = Role::whereNot('id', 3)->pluck('display_name','id');
        if($request->ajax()) {
            return json_encode(['data'=> $data]);
        }
        return $data;
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
            $query = new Permission();
            
            $roles = Role::with('permissions')->whereNot('id', 3)->get();
            $rolesHeader = [];
            $x = [];
            $centrals = [];
            foreach($roles as $r) {
                $x[$r->id] = $r->permissions;
                if($r->is_central_user == 1)
                {
                    $centrals[] = $r->id;
                }
            }

            // Search //input all searchable fields
            $search = $request->search;
            $query = $query->where(function($query) use ($search){
                $query->orWhere('name', 'like', "%".$search."%");   
                $query->orWhere('display_name', 'like', "%".$search."%");   
            });

            if($request->has('division')) {
                if(!empty($request->division)) {
                    $query = $query->where('division_name', $request->division);
                }
            }

            if($request->has('group')) {
                if(!empty($request->group)) {
                    $query = $query->where('group_name', $request->group);
                }
            }


            //default field for order
            $orderByCol = 'id';
        
            //input all orderable fields
            switch($orderColumnIndex){
                case '0':
                    $orderByCol = 'id';
                    break;
                case '1':
                    $orderByCol = 'name';
                    break;
            }

            $query = $query->orderBy($orderByCol, $orderBy);
            $recordsFiltered = $recordsTotal = $query->count();
            $data = $query->skip($skip)->take($pageLength)->get();

            $newData = [];
            foreach ($data as $value) {
                $action = substr(strrchr($value->name, '.'), 1);
                $result = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'display_name' => $value->display_name,
                    'group_name' => $value->group_name,
                    'actions' => $action
                ];
                foreach($x as $k => $z) {
                    $checked = $k == 1 ? 'checked' : '';
                    $disabled = $k == 1 ? 'disabled' : '';

                    $is_central = in_array($k, $centrals) ? '' : 'form-check-input-not-central';

                    $rolesHeader['r-'.$k] = '<div class="form-check form-switch">
                        <input class="form-check-input '.$is_central.'" type="checkbox" id="r'.$k.'-p'.$value->id.'" onchange="togglePermission('.$k.','.$value->id.')" '.$disabled.' '.$checked.'>
                    </div>';
                    $z->contains(function($v, $key) use (&$value, &$k, &$rolesHeader, &$disabled, &$checked, &$is_central) {
                        $b = $v->name == $value->name;
                        if($b === true) {
                            $checked = 'checked';
                            $rolesHeader['r-'.$k] = '<div class="form-check form-switch">
                                <input class="form-check-input '.$is_central.'" type="checkbox" id="r'.$k.'-p'.$value->id.'" onchange="togglePermission('.$k.','.$value->id.')" '.$disabled.' '.$checked.'>
                            </div>';
                        }
                    });
                }
                $result = array_merge($result, $rolesHeader);
                $newData[] = $result;
            }   
            
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $newData], 200);
        }

    }

    public function update_permission(Request $request)
    {
        if($request->ajax()){

            try {
                DB::beginTransaction();
                $data = $request->all();
                
                $role = Role::findOrFail($data['role_id']);
                $permission = Permission::findOrFail($data['permission_id']);
                if($data['value'] == 'true' || $data['value'] == true) {
                    $toggle = "ON";
                    $role->givePermissionTo($permission->name);
                } else {
                    $toggle = "OFF";
                    $role->revokePermissionTo($permission->name);
                }

                $permissions = array(
                    'permissions' => $permission->name
                );

                $this->logHistoriesRepo->rbacToggle($data['role_id'], $toggle, $permissions, $this->activityName);

                DB::commit();
                return json_encode([
                    'data'=> $role,
                    'permission' => $permission->display_name,
                    'status'=>'success',
                    'message'=>'Record has been updated.'
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in RbacController.update_permission.db_transaction.'
                ]);
            }
        }
    } 
}
