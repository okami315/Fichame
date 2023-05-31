<?php

namespace App\Entity;

use App\Repository\UserEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEventRepository::class)]
class UserEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $disponibility = 0;

    #[ORM\Column]
    private ?bool $coordination = false;

    #[ORM\Column]
    private ?bool $driving = false;

    #[ORM\ManyToOne(inversedBy: 'userEvents')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userEvents')]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisponibility(): ?int
    {
        return $this->disponibility;
    }

    public function setDisponibility(int $disponibility): self
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function isCoordination(): ?bool
    {
        return $this->coordination;
    }

    public function setCoordination(bool $coordination): self
    {
        $this->coordination = $coordination;

        return $this;
    }

    public function isDriving(): ?bool
    {
        return $this->driving;
    }

    public function setDriving(bool $driving): self
    {
        $this->driving = $driving;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
