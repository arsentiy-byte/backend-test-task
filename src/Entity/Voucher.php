<?php declare(strict_types=1);

namespace App\Entity;

use App\Enums\DiscountType;
use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['order_list'])]
    private ?string $code = null;

    #[ORM\Column(enumType: DiscountType::class)]
    private ?DiscountType $discountType = null;

    #[ORM\Column]
    private ?float $discount = null;

    /**
     * @param string $code
     * @param DiscountType $discountType
     * @param float $discount
     * @return self
     */
    public static function create(
        string $code,
        DiscountType $discountType,
        float $discount,
    ): self {
        $self = new self();

        $self
            ->setCode($code)
            ->setDiscountType($discountType)
            ->setDiscount($discount);

        return $self;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return DiscountType|null
     */
    public function getDiscountType(): ?DiscountType
    {
        return $this->discountType;
    }

    /**
     * @param DiscountType $discountType
     * @return self
     */
    public function setDiscountType(DiscountType $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return self
     */
    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @param float $price
     * @return float
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->getDiscountType() === DiscountType::PERCENTAGE) {
            return $price * $this->getDiscount() / 100;
        }

        return $this->getDiscount();
    }
}
