<?php declare(strict_types=1);

namespace App\Services\PaymentProcessorService;

use App\Contracts\Services\PaymentProcessorServiceContract;
use App\Entity\Order;
use App\Enums\PaymentProcessor;

class PaymentProcessorService implements PaymentProcessorServiceContract
{
    /**
     * @param Order $order
     * @return void
     */
    public function pay(Order $order): void
    {
        if ($order->getPaymentProcessor() === PaymentProcessor::PAYPAL) {
            PaypalPaymentProcessor::pay($order);

            return;
        }

        if ($order->getPaymentProcessor() === PaymentProcessor::STRIPE) {
            StripePaymentProcessor::pay($order);
        }
    }
}
