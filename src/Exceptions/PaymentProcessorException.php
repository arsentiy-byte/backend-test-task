<?php declare(strict_types=1);

namespace App\Exceptions;

use App\Entity\Order;
use App\Enums\PaymentProcessor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentProcessorException extends HttpException
{
    /**
     * @param Order $order
     * @param PaymentProcessor $paymentProcessor
     * @return self
     */
    public static function failedPayment(Order $order, PaymentProcessor $paymentProcessor): self
    {
        return new self(
            statusCode: Response::HTTP_BAD_REQUEST,
            message: \sprintf('Failed to pay order #%d by processor %s', $order->getId(), $paymentProcessor->value)
        );
    }
}
