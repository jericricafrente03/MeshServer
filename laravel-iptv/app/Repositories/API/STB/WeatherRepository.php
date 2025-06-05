<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\WeatherDailyForecastResource;
use App\Http\Resources\API\STB\WeatherHourlyForecastResource;
use App\Interfaces\API\STB\IWeatherRepository;
use App\Models\SystemSettings\SystemConfig;
use App\Models\WeatherDailyForecast;
use App\Models\WeatherHourlyForecast;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WeatherRepository implements IWeatherRepository
{
    public function curlWeatherApiForHourlyForecast($request)
    {
        $weatherData = $this->httpConnect('hourly');

        if (!isset($weatherData['hourly'])) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Invalid weather data received.'
            ], 500);
        }

        // Clear old data
        WeatherHourlyForecast::truncate();

        $hourlyData = $weatherData['hourly'];

        $weatherRecords = [];

        for ($i = 0; $i < 24; $i++) {
            $weatherRecords[] = [
                'date' => Carbon::parse($hourlyData['time'][$i])->format('Y-m-d H:i:s'),
                'temp' => $hourlyData['temperature_2m'][$i],
                'humidity' => $hourlyData['relative_humidity_2m'][$i],
                'wind_speed' => $hourlyData['wind_speed_10m'][$i],
                'description' => $this->getWeatherDescription($hourlyData['weather_code'][$i]),
                'icon' => $hourlyData['weather_code'][$i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // dd($weatherRecords);

        // **Bulk Insert using Eloquent**
        WeatherHourlyForecast::insert($weatherRecords);

        return response()->json([
            'result' => 'success',
            'message' => 'Weather data updated successfully.',
        ]);
    }

    public function curlWeatherApiForDailyForecast($request)
    {
        $weatherData = $this->httpConnect('daily');

        if (!isset($weatherData['hourly'])) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Invalid weather data received.'
            ], 500);
        }

        if (!isset($weatherData['daily'])) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Invalid weather data received.'
            ], 500);
        }

        // Clear old data
        WeatherDailyForecast::truncate();

        $dailyData = $weatherData['daily'];
        $hourlyData = $weatherData['hourly'];

        $weatherRecords = [];

        for ($i = 0; $i < 7; $i++) {
            $dailyDate = Carbon::parse($dailyData['time'][$i])->format('Y-m-d H:i:s');
            $index = $this->getHourlyIndexForDate($dailyDate.'T12:00', $hourlyData['time']);
            // $index = $this->getHourlyIndexForDate($dailyDate . 'T12:00', $hourlyTimes);

            $weatherRecords[] = [
                'date' => Carbon::parse($dailyData['time'][$i])->format('Y-m-d H:i:s'),
                'description' => $this->getWeatherDescription($dailyData['weather_code'][$i]),
                'humidity' => $hourlyData['relative_humidity_2m'][$index],
                'icon' => $dailyData['weather_code'][$i],
                'temp_min' => $dailyData['temperature_2m_min'][$i],
                'temp_max' => $dailyData['temperature_2m_max'][$i],

                'sunrise' => str_replace("T", ' ', $dailyData['sunrise'][$i]),
                'sunset' => str_replace("T", ' ', $dailyData['sunset'][$i]),

                'pressure' => $hourlyData['surface_pressure'][$index],
                'dew_point' => $hourlyData['dew_point_2m'][$index],

                'wind_speed' => $dailyData['wind_speed_10m_max'][$i],
                
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // dd(Carbon::parse($dailyData['time'][$i])->format('Y-m-d H:i:s'),);
        }

        // **Bulk Insert using Eloquent**
        WeatherDailyForecast::insert($weatherRecords);

        return response()->json([
            'result' => 'success',
            'message' => 'Weather data updated successfully.',
        ]);
    }

    public function getWeatherDailyForecast($request)
    {
        $data = WeatherDailyForecast::get();

        return WeatherDailyForecastResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    public function getWeatherHourlyForecast($request)
    {
        $data = WeatherHourlyForecast::get();

        return WeatherHourlyForecastResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    private function httpConnect($type)
    {
        // Get lat and lon from the database
        $config = SystemConfig::first();
        
        if (!$config) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Location configuration not found.'
            ], 404);
        }

        if (!$config->lat || !$config->lon) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Latitude or Longitude are not set.'
            ], 404);
        }

        $lat = $config->lat;
        $lon = $config->lon;

        switch ($type) {
            case 'hourly':
                $url = str_replace(['{lat}', '{lon}'], [$lat, $lon], env('WEATHER_API_HOURLY_URL'));
                break;

            case 'daily':
                $url = str_replace(['{lat}', '{lon}'], [$lat, $lon], env('WEATHER_API_DAILY_URL'));
                break;
            
            default:
                return response()->json([
                    'result' => 'failed',
                    'message' => 'Invalid weather type.'
                ], 404);
        }

        $response = Http::withOptions([
            'verify' => false, // Disable SSL verification if necessary
        ])->get($url);

        // Check for errors
        if ($response->failed()) {
            return response()->json([
                'result' => 'failed',
                'message' => 'Failed to fetch weather data.'
            ], 500);
        }

        // Parse response
        return $response->json();
    }

    private function getWeatherDescription($weatherCode)
    {
        $descriptions = [
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Fog',
            48 => 'Depositing rime fog',
            51 => 'Drizzle: Light intensity',
            53 => 'Drizzle: Moderate intensity',
            55 => 'Drizzle: Dense intensity',
            56 => 'Freezing Drizzle: Light intensity',
            57 => 'Freezing Drizzle: Dense intensity',
            61 => 'Rain: Slight intensity',
            63 => 'Rain: Moderate intensity',
            65 => 'Rain: Heavy intensity',
            66 => 'Freezing Rain: Light intensity',
            67 => 'Freezing Rain: Heavy intensity',
            71 => 'Snow fall: Slight intensity',
            73 => 'Snow fall: Moderate intensity',
            75 => 'Snow fall: Heavy intensity',
            77 => 'Snow grains',
            80 => 'Rain showers: Slight',
            81 => 'Rain showers: Moderate',
            82 => 'Rain showers: Violent',
            85 => 'Snow showers: Slight',
            86 => 'Snow showers: Heavy',
            95 => 'Thunderstorm: Slight',
            96 => 'Thunderstorm: Moderate',
            99 => 'Thunderstorm: Violent'
        ];

        return $descriptions[$weatherCode] ?? 'Unknown weather condition';
    }

    private function getHourlyIndexForDate($dailyDate, $hourlyTimes) {
        $index = array_search($dailyDate, $hourlyTimes);
            
        return $index;  // Return false if no match is found
    }
}