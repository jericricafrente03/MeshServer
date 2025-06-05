<?php

namespace App\Interfaces\General\Body\Devices;

interface IDeviceRepository
{
    public function get_data($request);
    public function detectDevices();
    public function update($request);
    public function delete($request);
    public function adbReboot($request);
    public function adbResetData($request);
}