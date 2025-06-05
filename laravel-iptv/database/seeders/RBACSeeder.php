<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RBACSeeder extends Seeder
{
    protected $toTruncate = ['role_has_permissions', 'model_has_permissions', 'model_has_roles', 'permissions', 'roles'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        
        Schema::enableForeignKeyConstraints();


        Model::reguard();
    }
}
