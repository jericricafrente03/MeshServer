<?php

namespace App\Interfaces\General\Body\Tv;

interface ITvChannelRepository
{
    public function getData($request);
    public function store($request);
    public function toggleEnable($request);
    public function changeOrder($request);
    public function update($request);
    public function delete($request);
}