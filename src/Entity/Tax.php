<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRepository::class)]
class Tax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $country = null;

    #[ORM\Column]
    private ?float $value = null;

    /**
     * @param string $code
     * @param string $country
     * @param float $value
     * @return self
     */
    public static function create(
        string $code,
        string $country,
        float $value,
    ): self {
        $self = new self();

        $self
            ->setCode($code)
            ->setCountry($country)
            ->setValue($value);

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
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return self
     */
    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }
}
