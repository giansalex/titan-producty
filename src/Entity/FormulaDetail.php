<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Material")
     * @ORM\JoinColumn(nullable=false)
     */
    private $materialId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formula")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formulaId;

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

    public function getMaterialId(): ?Material
    {
        return $this->materialId;
    }

    public function setMaterialId(?Material $materialId): self
    {
        $this->materialId = $materialId;

        return $this;
    }

    public function getFormulaId(): ?Formula
    {
        return $this->formulaId;
    }

    public function setFormulaId(?Formula $formulaId): self
    {
        $this->formulaId = $formulaId;

        return $this;
    }
}
