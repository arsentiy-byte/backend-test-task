<?php declare(strict_types=1);

namespace App\Handlers;

use App\Contracts\Services\PaymentProcessorServiceContract;
use App\DTO\PriceCalculationDTO;
use App\DTO\PurchaseDTO;
use App\Entity\Order;
use App\Entity\User;
use App\Enums\PaymentProcessor;
use App\Exceptions\PaymentProcessorException;
use App\Repository\OrderRepository;
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
        private PaymentProcessorServiceContract $paymentProcessorService,
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
            $this->paymentProcessorService->pay($order);
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
}
