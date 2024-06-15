<?php declare(strict_types=1);

namespace App\Enums;

enum PaymentProcessor: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
