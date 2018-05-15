<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
 */
class Material
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="30")
     * @ORM\Column(type="string", length=30)
     */
    private $code;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="10")
     * @ORM\Column(type="string", length=10)
     */
    private $unit;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(name="packing_price", type="float")
     */
    private $packingPrice;

    /**
     * @Assert\NotNull()
     * @Assert\Type("integer")
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPackingPrice(): ?float
    {
        return $this->packingPrice;
    }

    public function setPackingPrice(float $packingPrice): self
    {
        $this->packingPrice = $packingPrice;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
