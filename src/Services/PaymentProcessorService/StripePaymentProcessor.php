<?php declare(strict_types=1);

namespace App\Services\PaymentProcessorService;

use App\Entity\Order;

final readonly class StripePaymentProcessor
{
    /**
     * @param Order $order
     * @return void
     */
    public static function pay(Order $order): void
    {
        // ...
    }
}
