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
     */
    private $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     * @ORM\Column(type="string", length=50)
     */
    private $value;

    public function getCode(): ?string
    {
        return $this->code;
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
}
