<?php

namespace App\Interfaces\General\Body\Analytics;

interface IAnalyticRepository
{
    function getApps($request);
    function getYearCheckin($request);
    function getRooms($request);
    function getGuests($request);
    function getTop10TvChannels($request);
    function getTop10Fnbs($request);
    function getTop10ItemRequests($request);
    function getTop10ServiceRequests($request);
    function pingDevices($request);
    function getTvChannelData($request);
    function getTvChannelCounter($request);
    function getRegularMessagingData($request);
    function getBroadcastMessagingData($request);
    function getFnbData($request);
    function getFnbCounter($request);
    function getItemRequestData($request);
    function getItemRequestCounter($request);
    function getServiceRequestData($request);
    function getServiceRequestCounter($request);
}