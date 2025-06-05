<?php

namespace App\Interfaces\API\STB;

interface IAnalyticRepository
{
    function getAnalyticType($request);
    function postAnalytics($request);
}