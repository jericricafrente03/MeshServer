<?php

namespace Database\Seeders;

use App\Models\GeneralStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        GeneralStatus::truncate();

        Schema::enableForeignKeyConstraints();

        Model::reguard();

        $statuses = config('status');
        $storeStatuses = $statuses['general'];
 
        foreach ($storeStatuses as $status) {
            GeneralStatus::insert($status);
        }
    }
}
