<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductDetailRepository")
 */
class ProductDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @Assert\NotNull()
     * @Assert\Type("integer")
     * @ORM\Column(name="material_id", type="integer")
     */
    private $materialId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Material")
     */
    private $material;

    /**
     * @Exclude()
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="details")
     */
    private $product;

    public function getId()
    {
        return $this->id;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getMaterialId(): ?int
    {
        return $this->materialId;
    }

    public function setMaterialId(int $materialId): self
    {
        $this->materialId = $materialId;
        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
