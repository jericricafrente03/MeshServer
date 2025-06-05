<?php

namespace App\Interfaces\General\Body\Guests;

interface IRoomAssignmentRepository
{
    public function get_data($request);
    public function getBillingData($request);
    public function store($request);
    public function checkout($request);
    public function delete($request);
    public function changeRoom($request);

}