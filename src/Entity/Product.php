<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order_list'])]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var float|null
     */
    #[ORM\Column]
    private ?float $price = null;

    /**
     * @param string $title
     * @param float $price
     * @return self
     */
    public static function create(string $title, float $price): self
    {
        $self = new self();

        $self
            ->setTitle($title)
            ->setPrice($price);

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param Tax $tax
     * @param Voucher|null $voucher
     * @return float
     */
    public function calculatePrice(Tax $tax, ?Voucher $voucher = null): float
    {
        $price = $this->getPrice();
        $price -= $price * $tax->getValue() / 100;

        if (null !== $voucher) {
            $price -= $voucher->calculateDiscount($price);
        }

        return \max($price, 0);
    }
}
