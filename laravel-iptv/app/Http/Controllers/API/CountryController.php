<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function get_country()
    {
        // $response = Http::get('https://restcountries.com/v3.1/all?fields=name')->json();
        $response = Http::get('https://www.apicountries.com/countries')->json();
        
        // Check if data is not empty
        // if (!empty($response)) {
        //     // Truncate the Country table
        //     Country::truncate();

        //     $data = [];
        //     foreach ($response as $val) {
        //         array_push($data, ['name' => $val['name']['common']]);
        //     }

        //     // Insert the new data
        //     Country::insert($data);

        //     return "Success!";
        // } else {
        //     return "No data fetched.";
        // }
    }
}
