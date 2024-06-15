<?php declare(strict_types=1);

namespace App\ValueObject;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;

final readonly class PriceCalculationResultVO
{
    /**
     * @param Product $product
     * @param Tax $tax
     * @param Voucher|null $voucher
     * @param float $calculatedPrice
     */
    public function __construct(
        public Product $product,
        public Tax $tax,
        public ?Voucher $voucher,
        public float $calculatedPrice,
    ) {
    }
}
