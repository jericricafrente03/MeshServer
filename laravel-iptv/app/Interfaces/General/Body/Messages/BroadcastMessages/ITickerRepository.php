<?php

namespace App\Interfaces\General\Body\Messages\BroadcastMessages;

interface ITickerRepository
{
    public function getData($request);
    public function store($request);
    public function delete($request);
    public function resend($request);
}
