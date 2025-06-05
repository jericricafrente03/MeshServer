<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

use App\Models\PharmacyStore;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('pages');
 
        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::insertOrIgnore([
                'name' => $permission['name'], 
                'display_name' => $permission['display_name'], 
                'division_name' => $permission['division_name'], 
                'group_name' => $permission['group_name'], 
                'guard_name' => 'web'
            ]);
        }
    }
}
