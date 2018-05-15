<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @Assert\Length(max="255")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notes;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @ORM\Column(type="float")
     */
    private $baseAmount;

    /**
     * @Assert\NotNull()
     * @Assert\Type("integer")
     * @ORM\Column(name="formula_id", type="integer")
     */
    private $formulaId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formula")
     */
    private $formula;

    /**
     * @Type("ArrayCollection<App\Entity\ProductDetail>")
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ProductDetail",
     *     mappedBy="product",
     *     cascade={"persist", "remove"}
     * )
     */
    private $details;

    /**
     * @Exclude()
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getBaseAmount(): ?float
    {
        return $this->baseAmount;
    }

    public function setBaseAmount(float $baseAmount): self
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    public function getFormulaId(): ?int
    {
        return $this->formulaId;
    }

    public function setFormulaId(int $formulaId): self
    {
        $this->formulaId = $formulaId;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ProductDetail[]
     */
    public function getDetails() : Collection
    {
        return $this->details;
    }

    public function addDetail(ProductDetail $detail)
    {
        $this->details[] = $detail;
    }
}
