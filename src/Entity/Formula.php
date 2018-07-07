<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormulaRepository")
 * @UniqueEntity("name")
 */
class Formula
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
     * @Assert\Type("numeric")
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

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
     * @Assert\Valid()
     * @Assert\Count(min="1")
     * @Serializer\Type("ArrayCollection<App\Entity\FormulaDetail>")
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\FormulaDetail",
     *     mappedBy="formula",
     *     cascade={"persist", "remove"}
     * )
     */
    private $details;

    /**
     * @Serializer\Exclude()
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Formula constructor.
     */
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
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
     * @return Collection|FormulaDetail[]
     */
    public function getDetails() : Collection
    {
        return $this->details;
    }

    public function addDetail(FormulaDetail $detail)
    {
        $this->details[] = $detail;
    }
}
