<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Entity\Order;
use App\Enums\PaymentProcessor;
use Exception;

class PaymentProcessorException extends Exception
{
    /**
     * @param Order $order
     * @param PaymentProcessor $paymentProcessor
     * @return self
     */
    public static function failedPayment(Order $order, PaymentProcessor $paymentProcessor): self
    {
        return new self(\sprintf('Failed to pay order #%d by processor %s', $order->getId(), $paymentProcessor->value));
    }
}
