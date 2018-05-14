<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormulaDetailRepository")
 */
class FormulaDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="App\Entity\Material")
     * @ORM\JoinColumn(nullable=false)
     */
    private $material;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formula")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $formula;

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

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getFormula(): ?Formula
    {
        return $this->formula;
    }

    public function setFormula(?Formula $formula): self
    {
        $this->formula = $formula;

        return $this;
    }
}
