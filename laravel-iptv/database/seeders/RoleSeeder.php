<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PharmacyStore;
use App\Models\PharmacyStaff;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        $password = 'Pass1234.';
        
        $menuGeneralGroupPermissions = Permission::where('division_name','general')->pluck('group_name','name')->all();
        $menuSettingsGroupPermissions = Permission::where('division_name','system_settings')->pluck('group_name','name')->all();

        
        $keysMenuGeneralGroupPermissions = array_keys($menuGeneralGroupPermissions);
        
        // Menu Settings Group Permissions
        $user = array_keys($menuSettingsGroupPermissions, 'user');
        $role = array_keys($menuSettingsGroupPermissions, 'role');
        $rbac = array_keys($menuSettingsGroupPermissions, 'rbac');
        
        
        $insertRoles = Role::insertOrIgnore([
            ['name' => 'super-admin', 'level' => 1, 'display_name' => 'Super Admin', 'guard_name' => 'web', 'description' => 'Can access ALL PAGES - exempted to all rbac'],
            ['name' => 'admin', 'level' => 2, 'display_name' =>  'Administrator', 'guard_name' => 'web', 'description' => 'Can access all pages'],
        ]);
        
        $rolePermissions = [
            'admin' => array_merge(
                $keysMenuGeneralGroupPermissions
            )
            ,
        ];

        $roles = Role::all();

        /**
         * DEFAULT ROLES
         */

        User::truncate();
        // Employee::truncate();

        $users = [
            'super-admin' => ['firstname' => 'Super', 'lastname' => 'Admin', 'username' => 'superadmin'],
            'admin' => ['firstname' => 'Application', 'lastname' => 'Admin', 'username' => 'admin'],
        ];
        foreach($roles as $role) {
            
            $username = isset($users[$role->name]) ? $users[$role->name]['username'] : $role->name;
            $firstname = isset($users[$role->name]) ? $users[$role->name]['firstname'] : $role->display_name;
            $lastname = isset($users[$role->name]) ? $users[$role->name]['lastname'] : 'User';
            

            $user = User::create([
                'name' => $username, 
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $username.'@bittelasia.com',
                'password' => Hash::make($password),
                'role_id' => $role->id,
                'initials_random_color' =>  rand(1, 10)
            ]);

            $user->assignRole($role->name);


            if(isset($rolePermissions[$role->name])) {
                $permissions = $rolePermissions[$role->name];
               
                foreach($permissions as $pname) {
                    
                    // $permission = Permission::findOrCreate($pname, 'web');

                    if (!Permission::where('name', $pname)->where('guard_name', 'web')->exists()) {
                        $permission = Permission::create(['name' => $pname, 'guard_name' => 'web']);
                    } else {
                        $permission = Permission::where('name', $pname)->where('guard_name', 'web')->first();
                    }
                    
                    $role->givePermissionTo($permission);
                }
            }
        }

        Role::insertOrIgnore([
            ['name' => 'stb', 'level' => 1, 'display_name' =>  'Device', 'guard_name' => 'api', 'description' => 'STB Device'],  
        ]);

        Schema::enableForeignKeyConstraints();
        

        Model::reguard();

    }
}
