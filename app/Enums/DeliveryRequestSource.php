<?php

namespace App\Enums;

enum DeliveryRequestSource: string
{
    case Manual = 'manual';
    case PartnerWeb = 'partner_web';
    case Api = 'api';
}
