<?php

namespace App\Interfaces\SystemSettings\DeviceAdb;

interface IManagerRepository
{
    function getData($request);
    function getApkList($request);
    function groupInstall($request);
    function groupUninstall($request);
    function settings($request);
    function screenCapture($request);
    function screenRecord($request);
}