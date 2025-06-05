<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function get_data(Request $request)
    {
        if($request->ajax()){
            $role_id = $request['role_id'] ?? null;
            if(!empty($role_id)){
                $role = Role::where('roles.id', $role_id)
                    ->with('permissions')
                    ->first();
                $permissions = $role->permissions;
                $selected = [];
                foreach($permissions as $permission) {
                    $selected[] = $permission['name'];
                }

                $authRole = Role::where('roles.id', Auth::user()->role->id)
                    ->with('permissions')
                    ->first();
                $authPermissions = $authRole->permissions;
                $selectionPermissions = [];
                foreach($authPermissions as $permission) {
                    $selectionPermissions[] = array(
                        "display_name" => $permission['display_name'],
                        "name" => $permission['name']
                    );
                }

                $data = [
                    'permissions' => $selected,
                    'all' => $selectionPermissions//Permission::get()
                ];
            } else {
                $role = Role::where('roles.id', Auth::user()->role->id)
                    ->with('permissions')
                    ->first();
                $permissions = $role->permissions;
                $selected = [];
                foreach($permissions as $permission) {
                    $selected[] = array(
                        "display_name" => $permission['display_name'],
                        "name" => $permission['name']
                    );
                }
                $data = [
                    'permissions' => $selected,
                    'all' => Permission::get()
                ];
                // $data = Permission::get(); 
            }
            
            //if user is superadmin it will override the first condition result
            if(Auth::user()->role_id == 1){
                if(!empty($role_id)){
                    $role = Role::where('roles.id', $role_id)
                        ->with('permissions')
                        ->first();
                    $permissions = $role->permissions;
                    $selected = [];
                    foreach($permissions as $permission) {
                        $selected[] = $permission['name'];
                    }
    
                    $authRole = Role::where('roles.id', Auth::user()->role->id)
                        ->with('permissions')
                        ->first();
                    $authPermissions = $authRole->permissions;
                    $selectionPermissions = [];
                    foreach($authPermissions as $permission) {
                        $selectionPermissions[] = array(
                            "display_name" => $permission['display_name'],
                            "name" => $permission['name']
                        );
                    }
    
                    $data = [
                        'permissions' => $selected,
                        'all' => Permission::get()
                    ];
                } 
                else{
                    $permissions = Permission::get()->toArray();
                    $selected = [];
                    foreach($permissions as $permission) {
                        $selected[] = array(
                            "display_name" => $permission['display_name'],
                            "name" => $permission['name']
                        );
                    }
                    $data = [
                        'permissions' => $selected,
                        'all' => Permission::get()
                    ];
                }
            }

            return json_encode([
                'data'=> $data,
            ]);
        }

    }
}
