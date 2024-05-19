<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Talk $Talk = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $fromUser = null;

    #[ORM\ManyToOne]
    private ?User $toUser = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sendAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Text = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTalk(): ?Talk
    {
        return $this->Talk;
    }

    public function setTalk(?Talk $Talk): static
    {
        $this->Talk = $Talk;

        return $this;
    }

    public function getFromUser(): ?User
    {
        return $this->fromUser;
    }

    public function setFromUser(?User $fromUser): static
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    public function getToUser(): ?User
    {
        return $this->toUser;
    }

    public function setToUser(?User $toUser): static
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeImmutable $sendAt): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(?string $Text): static
    {
        $this->Text = $Text;

        return $this;
    }
}
