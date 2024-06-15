<?php declare(strict_types=1);

namespace App\Handlers;

use App\DTO\PriceCalculationDTO;
use App\DTO\PurchaseDTO;
use App\Entity\Order;
use App\Entity\User;
use App\Enums\PaymentProcessor;
use App\Exceptions\PaymentProcessorException;
use App\Repository\OrderRepository;
use App\Services\PaymentProcessorService\PaypalPaymentProcessor;
use App\Services\PaymentProcessorService\StripePaymentProcessor;
use App\ValueObject\PriceCalculationResultVO;

readonly class PurchaseHandler
{
    /**
     * @param PriceCalculationHandler $priceCalculationHandler
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        private PriceCalculationHandler $priceCalculationHandler,
        private OrderRepository $orderRepository,
    ) {
    }

    /**
     * @param PriceCalculationDTO $priceCalculationDTO
     * @param PurchaseDTO $dto
     * @param User $user
     * @return Order
     * @throws PaymentProcessorException
     */
    public function handle(PriceCalculationDTO $priceCalculationDTO, PurchaseDTO $dto, User $user): Order
    {
        $result = $this->priceCalculationHandler->handle($priceCalculationDTO);

        $order = $this->createOrder($result, $user, PaymentProcessor::from($dto->paymentProcessor));

        try {
            $this->pay($order);
        } catch (PaymentProcessorException $exception) {
            $order->failOrder();
            $this->orderRepository->save($order);

            throw $exception;
        }

        $order->completeOrder();
        $this->orderRepository->save($order);

        return $order;
    }

    /**
     * @param PriceCalculationResultVO $result
     * @param User $user
     * @param PaymentProcessor $paymentProcessor
     * @return Order
     */
    private function createOrder(
        PriceCalculationResultVO $result,
        User $user,
        PaymentProcessor $paymentProcessor
    ): Order {
        $order = Order::create(
            $result->product,
            $user,
            $result->calculatedPrice,
            $paymentProcessor,
            $result->tax,
            $result->voucher,
        );

        $this->orderRepository->save($order);

        return $order;
    }

    /**
     * @param Order $order
     * @return void
     */
    private function pay(Order $order): void
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
