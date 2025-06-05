<?php

namespace App\Interfaces\General\Body\Messages\BroadcastMessages;

interface IAdvertisementRepository
{
    public function getData($request);
    public function store($request);
    public function delete($request);
    public function resend($request);
}