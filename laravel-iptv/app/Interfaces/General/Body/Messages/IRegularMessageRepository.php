<?php

namespace App\Interfaces\General\Body\Messages;

interface IRegularMessageRepository
{
    public function getData($request);
    public function store($request);
    public function delete($request);
    public function update($request);
}