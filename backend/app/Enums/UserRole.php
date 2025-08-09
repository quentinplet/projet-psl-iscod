<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'acheteur';
    case LOGISTICIAN = 'logisticien';
    case MANAGER = 'gestionnaire';
}
