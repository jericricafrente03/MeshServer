<?php

namespace App\Interfaces\General\Body\Devices;

interface IDeviceMonitorRepository
{
    public function get_devices();
    public function chartData($request);
}