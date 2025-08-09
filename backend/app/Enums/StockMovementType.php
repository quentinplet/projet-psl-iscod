<?php

namespace App\Enums;

enum StockMovementType: string
{
    case IN = 'entrée';
    case OUT = 'sortie';
    case ADJUSTMENT = 'ajustement';
}
