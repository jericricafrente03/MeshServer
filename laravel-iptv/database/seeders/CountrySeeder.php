<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\TimeZone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://www.apicountries.com/countries')->json();
        $tzResponse = Http::get('https://www.timeapi.io/api/timezone/availabletimezones')->json();

        // Check if data is not empty
        if (!empty($response)) {
            // Truncate the Country table
            Country::truncate();

            $data = [];
            foreach ($response as $val) {
                array_push($data, [
                    'country_name' => $val['name'],
                    'country_code' => $val['alpha2Code'],
                    'city_capital' => isset($val['capital']) ? $val['capital'] : 'N/A', // Check if 'capital' exists
                ]);
            }

            // Insert the new data
            Country::insert($data);
        }

         // Check if data is not empty
         if (!empty($tzResponse)) {
            // Truncate the Country table
            TimeZone::truncate();

            $data = [];
            foreach ($tzResponse as $val) {
                array_push($data, [
                    'zone_name' => $val
                ]);
            }

            // Insert the new data
            TimeZone::insert($data);
        }
    }
}
