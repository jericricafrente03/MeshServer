<?php

namespace App\Interfaces\General;

interface SearchInterface
{   
    public function searchDeviceGroup($request);
    public function searchRoomCategories($request);
    public function searchRoomStatuses($request);
}