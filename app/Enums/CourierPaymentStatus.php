<?php

namespace App\Enums;

enum CourierPaymentStatus: string
{
    case Pending = 'pending';
    case Released = 'released';
    case Paid = 'paid';
    case Blocked = 'blocked';
}
