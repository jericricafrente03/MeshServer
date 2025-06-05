<?php

namespace App\Interfaces\General\Body\VideoAds;

interface IVideoAdsRepository
{
    function getData($request);
    function store($request);
    function toggleEnable($request);
    function update($request);
    function delete($request);
    function changeOrder($request);
}