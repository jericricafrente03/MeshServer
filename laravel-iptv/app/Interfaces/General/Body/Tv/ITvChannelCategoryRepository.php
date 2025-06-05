<?php

namespace App\Interfaces\General\Body\Tv;

interface ITvChannelCategoryRepository
{
    public function getData($request);
    public function store($request);
    public function update($request);
    public function changeOrder($request);
    public function delete($request);
}