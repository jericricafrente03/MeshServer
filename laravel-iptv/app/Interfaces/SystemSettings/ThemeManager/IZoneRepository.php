<?php

namespace App\Interfaces\SystemSettings\ThemeManager;

interface IZoneRepository
{
    function getData($request);
    function store($request);
    function update($request);
    function delete($request);
}