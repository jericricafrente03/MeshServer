<?php

namespace App\Interfaces\SystemSettings\ThemeManager;

interface IThemeRepository
{
    function getData($request);
    function store($request);
    function toggleDefault($request);
    function updateTheme($request);
    function getThemeZoneData($request);
    function updateThemeZone($request);
    function delete($request);
    function assignRoom($request);
    function getThemeApplicationData($request);
    function updateThemeApplication($request);
    function toggleEnable($request);
}