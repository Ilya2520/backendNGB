<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $amount = null;

    #[ORM\ManyToOne(inversedBy: 'bankAccounts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?array $information = null;

    #[ORM\ManyToOne]
    private ?BankAccountType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Status = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInformation(): ?array
    {
        return $this->information;
    }

    public function setInformation(?array $information): static
    {
        $this->information = $information;

        return $this;
    }

    public function getType(): ?BankAccountType
    {
        return $this->type;
    }

    public function setType(?BankAccountType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): static
    {
        $this->Status = $Status;

        return $this;
    }
    
    #[\Symfony\Component\Serializer\Annotation\SerializedName('userId')]
    public function getToBankAccountId(): ?int
    {
        return $this->getUser()->getId();
    }
}
