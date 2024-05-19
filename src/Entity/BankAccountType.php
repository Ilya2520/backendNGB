<?php

namespace App\Entity;

use App\Repository\BankAccountTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountTypeRepository::class)]
class BankAccountType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Type = null;

    #[ORM\Column(nullable: true, options: ["default" => 0])]
    private ?float $Conditions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->Conditions;
    }

    public function setConditions(float $Conditions): static
    {
        $this->Conditions = $Conditions;

        return $this;
    }
}
