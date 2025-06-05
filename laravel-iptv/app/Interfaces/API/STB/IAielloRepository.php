<?php

namespace App\Interfaces\API\STB;

interface IAielloRepository
{
    function toggleTvPower($request);
    function volumeChange($request);
    function getTvChannels($request);
    function openApplication($request);
    function changeTvChannel($request);
}