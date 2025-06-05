<?php

namespace App\Interfaces\General\Body\Guests;

interface IRoomCategoryRepository
{
    public function get_data($request);
    public function store($request);
    public function update($request);
    public function delete($request);
    public function changeOrder($request);
}