<?php

namespace App\Interfaces\API\STB;

interface IRegularMessageRepository
{
    public function getMessage($request);
    public function readMessage($request);
    public function deleteMessage($request);
}