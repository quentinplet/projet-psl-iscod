<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'en attente de préparation';
    case InProgress = 'en cours de préparation';
    case ReadyForShipment = 'prête pour expédition';
    case Shipped = 'expédiée';
    case Delivered = 'livrée';
    case Canceled = 'annulée';
}
