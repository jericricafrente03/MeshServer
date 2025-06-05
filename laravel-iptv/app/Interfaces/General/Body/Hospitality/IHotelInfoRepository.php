<?php

namespace App\Interfaces\General\Body\Hospitality;

interface IHotelInfoRepository
{
    public function getData($request);
    public function store($request);
    public function toggleEnable($request);
    public function update($request);
    public function delete($request);
    public function changeOrder($request);
}