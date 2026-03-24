<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Available = 'available';
    case Accepted = 'accepted';
    case InPickup = 'in_pickup';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Rejected = 'rejected';
    case Canceled = 'canceled';
}
