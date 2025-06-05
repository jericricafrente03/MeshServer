<?php

namespace App\Interfaces\SystemSettings\DeviceAdb\Files;

interface IScreenRecordRepository
{
    function getData($request);
    function delete($request);
    function checkTableChanges($request);
}