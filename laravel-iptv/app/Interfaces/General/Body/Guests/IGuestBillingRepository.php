<?php

namespace App\Interfaces\General\Body\Guests;

interface IGuestBillingRepository
{
    public function getData($request);
    public function update($request);
    public function updatePayment($request);
}