<?php declare(strict_types=1);

namespace App\Contracts\Services;

use App\Entity\Order;

interface PaymentProcessorServiceContract
{
    /**
     * @param Order $order
     * @return void
     */
    public function pay(Order $order): void;
}
