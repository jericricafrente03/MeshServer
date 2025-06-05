<?php 

namespace App\Interfaces\General\Body\Guests;

interface IGuestRepository
{
    public function getData($request);
    public function store($request);
    public function update($request);
    public function delete($request);
}