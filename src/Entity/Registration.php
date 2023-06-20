<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checkout = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createcheckin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createcheckout = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckin(): ?\DateTimeInterface
    {
        return $this->checkin;
    }

    public function setCheckin(?\DateTimeInterface $checkin): self
    {
        $this->checkin = $checkin;

        return $this;
    }

    public function getCheckout(): ?\DateTimeInterface
    {
        return $this->checkout;
    }

    public function setCheckout(?\DateTimeInterface $checkout): self
    {
        $this->checkout = $checkout;

        return $this;
    }

    public function getCreatecheckin(): ?\DateTimeInterface
    {
        return $this->createcheckin;
    }

    public function setCreatecheckin(?\DateTimeInterface $createcheckin): self
    {
        $this->createcheckin = $createcheckin;

        return $this;
    }

    public function getCreatecheckout(): ?\DateTimeInterface
    {
        return $this->createcheckout;
    }

    public function setCreatecheckout(?\DateTimeInterface $createcheckout): self
    {
        $this->createcheckout = $createcheckout;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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
}
