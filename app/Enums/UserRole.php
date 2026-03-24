<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Partner = 'partner';
    case Courier = 'courier';
}
