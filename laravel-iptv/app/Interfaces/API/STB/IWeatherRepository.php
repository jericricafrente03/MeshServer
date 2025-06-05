<?php

namespace App\Interfaces\API\STB;

interface IWeatherRepository
{
    function curlWeatherApiForHourlyForecast($request);
    function curlWeatherApiForDailyForecast($request);
    function getWeatherDailyForecast($request);
    function getWeatherHourlyForecast($request);
}