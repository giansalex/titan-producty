<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitRepository")
 */
class Unit
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="10")
     * @ORM\Id()
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $value;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="20")
     * @ORM\Column(type="string", length=20)
     * @var string
     */
    private $type;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
