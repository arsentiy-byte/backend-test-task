<?php declare(strict_types=1);

namespace App\Tests\Unit\Handlers;

use App\DTO\PriceCalculationDTO;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Handlers\PriceCalculationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceCalculationHandlerTest extends KernelTestCase
{
    private PriceCalculationHandler $handler;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->handler = self::getContainer()->get(PriceCalculationHandler::class);
    }

    public function testPriceCalculation(): void
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Voucher $voucher */
        $voucher = $this->entityManager->getRepository(Voucher::class)->findOneBy(['code' => 'P10']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['country' => 'Германия']);

        $dto = new PriceCalculationDTO(
            $product->getId(),
            $tax->getCode(),
            $voucher->getCode(),
        );

        $result = $this->handler->handle($dto);

        self::assertSame($product->calculatePrice($tax, $voucher), $result->calculatedPrice);
    }

    public function testPriceCalculationWithoutCoupon(): void
    {
        /** @var Product $product */
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['title' => 'Iphone']);
        /** @var Tax $tax */
        $tax = $this->entityManager->getRepository(Tax::class)->findOneBy(['country' => 'Германия']);

        $dto = new PriceCalculationDTO(
            $product->getId(),
            $tax->getCode(),
            null,
        );

        $result = $this->handler->handle($dto);

        self::assertSame($product->calculatePrice($tax), $result->calculatedPrice);
    }
}
