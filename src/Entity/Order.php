<?php declare(strict_types=1);

namespace App\Entity;

use App\Enums\OrderStatus;
use App\Enums\PaymentProcessor;
use App\Repository\OrderRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order_list'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order_list'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order_list'])]
    private ?User $client = null;

    #[ORM\Column]
    #[Groups(['order_list'])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(['order_list'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['order_list'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    #[Groups(['order_list'])]
    private ?OrderStatus $status = null;

    #[ORM\Column(enumType: PaymentProcessor::class)]
    #[Groups(['order_list'])]
    private ?PaymentProcessor $paymentProcessor = null;

    #[ORM\ManyToOne]
    #[Groups(['order_list'])]
    private ?Voucher $voucher = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order_list'])]
    private ?Tax $tax = null;

    /**
     * @param Product $product
     * @param User $client
     * @param float $price
     * @param PaymentProcessor $paymentProcessor
     * @param Tax $tax
     * @param Voucher|null $voucher
     * @return self
     */
    public static function create(
        Product $product,
        User $client,
        float $price,
        PaymentProcessor $paymentProcessor,
        Tax $tax,
        ?Voucher $voucher,
    ): self {
        $self = new self();

        $self
            ->setProduct($product)
            ->setClient($client)
            ->setPrice($price)
            ->setStatus(OrderStatus::CREATED)
            ->setPaymentProcessor($paymentProcessor)
            ->setTax($tax)
            ->setVoucher($voucher);

        return $self;
    }

    /**
     * @param PrePersistEventArgs $eventArgs
     * @return void
     */
    #[ORM\PrePersist]
    public function updateFieldsOnPrePersist(PrePersistEventArgs $eventArgs): void
    {
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }

        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getClient(): ?User
    {
        return $this->client;
    }

    /**
     * @param User|null $client
     * @return $this
     */
    public function setClient(?User $client): static
    {
        $this->client = $client;

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
     * @return $this
     */
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return OrderStatus|null
     */
    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $status
     * @return $this
     */
    public function setStatus(OrderStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function failOrder(): void
    {
        $this->setStatus(OrderStatus::FAILED);
    }

    public function completeOrder(): void
    {
        $this->setStatus(OrderStatus::COMPLETED);
    }

    public function getPaymentProcessor(): ?PaymentProcessor
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(PaymentProcessor $paymentProcessor): static
    {
        $this->paymentProcessor = $paymentProcessor;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): static
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): static
    {
        $this->tax = $tax;

        return $this;
    }
}
