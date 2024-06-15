<?php declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case CREATED = 'created';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
