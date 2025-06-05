<?php

namespace App\Interfaces\SystemSettings\ThemeManager;

interface IDefaultAppRepository
{
    function getData($request);
    function toggleEnable($request);
    function store($request);
    function update($request);
    function delete($request);
}