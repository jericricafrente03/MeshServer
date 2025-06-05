<?php

namespace App\Interfaces\API\STB;

interface IDeviceRepository
{
    function getLanguage($request);
    function getWifiQrCode($request);
    function enterNetflix($request);
    function clearCache($request);
}