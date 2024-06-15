<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Validators\EntityExists\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class PriceCalculationDTO
{
    /**
     * @param int $product
     * @param string $taxNumber
     * @param string|null $couponCode
     */
    public function __construct(
        #[Assert\NotBlank]
        #[EntityExists(entity: Product::class)]
        public readonly int $product,
        #[Assert\NotBlank]
        #[EntityExists(entity: Tax::class, property: 'code')]
        public readonly string $taxNumber,
        #[EntityExists(entity: Voucher::class, property: 'code')]
        public readonly ?string $couponCode,
    ) {
    }
}
