<?php

namespace App\Interfaces\General\Body\Notifications;

interface INotificationRepository
{
    function getNotifications($request);
    function readNotification($request);
}