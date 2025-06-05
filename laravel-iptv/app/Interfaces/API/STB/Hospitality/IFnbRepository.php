<?php

namespace App\Interfaces\API\STB\Hospitality;

interface IFnbRepository
{
    function getFnbs($request);
    function getRecommendedFnbs($request);
}