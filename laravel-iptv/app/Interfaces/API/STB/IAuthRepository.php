<?php

namespace App\Interfaces\API\STB;

interface IAuthRepository 
{
    public function stb_register($request);
    public function stb_time();
    public function stb_authenticateDevice($data);
    function checkToken($request);
}