<?php declare(strict_types=1);

namespace App\Handlers;

use App\DTO\PriceCalculationDTO;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use App\Repository\VoucherRepository;

readonly class PriceCalculationHandler
{
    /**
     * @param ProductRepository $productRepository
     * @param VoucherRepository $voucherRepository
     * @param TaxRepository $taxRepository
     */
    public function __construct(
        private ProductRepository $productRepository,
        private VoucherRepository $voucherRepository,
        private TaxRepository $taxRepository,
    ) {
    }

    /**
     * @param PriceCalculationDTO $dto
     * @return float
     */
    public function handle(PriceCalculationDTO $dto): float
    {
        /** @var Product $product */
        $product = $this->productRepository->findOneBy(['id' => $dto->product]);
        /** @var Tax $tax */
        $tax = $this->taxRepository->findOneBy(['code' => $dto->taxNumber]);
        /** @var Voucher|null $voucher */
        $voucher = $this->voucherRepository->findOneBy(['code' => $dto->couponCode]);

        return $product->calculatePrice($tax, $voucher);
    }
}
