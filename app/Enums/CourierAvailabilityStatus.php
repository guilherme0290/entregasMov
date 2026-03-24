<?php

namespace App\Enums;

enum CourierAvailabilityStatus: string
{
    case Online = 'online';
    case Offline = 'offline';
    case Busy = 'busy';
    case Blocked = 'blocked';
}
