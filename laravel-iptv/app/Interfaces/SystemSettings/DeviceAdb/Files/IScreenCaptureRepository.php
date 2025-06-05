<?php 

namespace App\Interfaces\SystemSettings\DeviceAdb\Files;

interface IScreenCaptureRepository
{
    function getData($request);
    function delete($request);
    function checkTableChanges($request);
}