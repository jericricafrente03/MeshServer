<?php

namespace App\Interfaces\SystemSettings\DeviceAdb;

interface IApkRepository
{
    function getData($request);
    function delete($request);
    function store($request);
}