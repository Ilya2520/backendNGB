<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?BankAccount $fromBankAccount = null;

    #[ORM\ManyToOne]
    #[Ignore]
    private ?BankAccount $toBankAccount = null;

    #[ORM\Column(length: 255)]
    private ?string $Status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?TransactionType $TransactionType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getFromBankAccount(): ?BankAccount
    {
        return $this->fromBankAccount;
    }

    public function setFromBankAccount(?BankAccount $fromBankAccount): static
    {
        $this->fromBankAccount = $fromBankAccount;

        return $this;
    }

    public function getToBankAccount(): ?BankAccount
    {
        return $this->toBankAccount;
    }

    public function setToBankAccount(?BankAccount $toBankAccount): static
    {
        $this->toBankAccount = $toBankAccount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getTransactionType(): ?TransactionType
    {
        return $this->TransactionType;
    }

    public function setTransactionType(?TransactionType $TransactionType): static
    {
        $this->TransactionType = $TransactionType;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(?string $Message): static
    {
        $this->Message = $Message;

        return $this;
    }
    
    #[SerializedName('fromBankAccountId')]
    public function getFromBankAccountId(): ?int
    {
        return $this->fromBankAccount?->getId();
    }
    
    #[SerializedName('toBankAccountId')]
    public function getToBankAccountId(): ?int
    {
        return $this->toBankAccount?->getId();
    }
}
