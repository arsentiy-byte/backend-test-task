<?php declare(strict_types=1);

namespace App\Handlers;

use App\DTO\PriceCalculationDTO;
use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use App\Repository\VoucherRepository;
use App\ValueObject\PriceCalculationResultVO;

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
     * @return PriceCalculationResultVO
     */
    public function handle(PriceCalculationDTO $dto): PriceCalculationResultVO
    {
        /** @var Product $product */
        $product = $this->productRepository->findOneBy(['id' => $dto->product]);
        /** @var Tax $tax */
        $tax = $this->taxRepository->findOneBy(['code' => $dto->taxNumber]);
        /** @var Voucher|null $voucher */
        $voucher = $this->voucherRepository->findOneBy(['code' => $dto->couponCode]);

        $calculatedPrice = $product->calculatePrice($tax, $voucher);

        return new PriceCalculationResultVO(
            $product,
            $tax,
            $voucher,
            $calculatedPrice,
        );
    }
}
