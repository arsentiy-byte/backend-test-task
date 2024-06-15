<?php declare(strict_types=1);

namespace App\Tests\Unit\Handlers;

use App\DTO\PriceCalculationDTO;
use App\DTO\PurchaseDTO;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\User;
use App\Entity\Voucher;
use App\Enums\PaymentProcessor;
use App\Exceptions\PaymentProcessorException;
use App\Handlers\PurchaseHandler;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PurchaseHandlerTest extends KernelTestCase
{
    private PurchaseHandler $handler;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->handler = self::getContainer()->get(PurchaseHandler::class);
    }

    /**
     * @throws PaymentProcessorException
     */
    public function testPurchase(): void
    {
        $faker = Factory::create();

        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Voucher $voucher */
        $voucher = $this->entityManager->getRepository(Voucher::class)->findOneBy(['code' => 'P10']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['country' => 'Германия']);
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        /** @var PaymentProcessor $paymentProcessor */
        $paymentProcessor = $faker->randomElement(PaymentProcessor::cases());

        $priceCalculationDTO = new PriceCalculationDTO(
            $product->getId(),
            $tax->getCode(),
            $voucher->getCode(),
        );

        $dto = new PurchaseDTO($paymentProcessor->value);

        $order = $this->handler->handle($priceCalculationDTO, $dto, $user);

        self::assertNotEmpty($order->getId());
        self::assertSame($product->getId(), $order->getProduct()->getId());
        self::assertSame($user->getId(), $order->getClient()->getId());
        self::assertSame($voucher->getCode(), $order->getVoucher()->getCode());
        self::assertSame($tax->getCode(), $order->getTax()->getCode());
        self::assertSame($product->calculatePrice($tax, $voucher), $order->getPrice());
        self::assertNotEmpty($order->getCreatedAt());
        self::assertNotEmpty($order->getUpdatedAt());
        self::assertSame($paymentProcessor, $order->getPaymentProcessor());
    }

    /**
     * @throws PaymentProcessorException
     */
    public function testPurchaseWithoutCoupon(): void
    {
        $faker = Factory::create();

        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['country' => 'Германия']);
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        /** @var PaymentProcessor $paymentProcessor */
        $paymentProcessor = $faker->randomElement(PaymentProcessor::cases());

        $priceCalculationDTO = new PriceCalculationDTO(
            $product->getId(),
            $tax->getCode(),
            null,
        );

        $dto = new PurchaseDTO($paymentProcessor->value);

        $order = $this->handler->handle($priceCalculationDTO, $dto, $user);

        self::assertNull($order->getVoucher());
    }
}
