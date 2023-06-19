<?php

namespace App\Entity;

use App\Repository\UserEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEventRepository::class)]
class UserEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $disponibility = null;

    #[ORM\Column(nullable: true)]
    private ?bool $coordination = false;

    #[ORM\Column]
    private ?bool $driving = false;

    #[ORM\ManyToOne(inversedBy: 'userEvents')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userEvents')]
    private ?Event $event = null;

    #[ORM\Column]
    private ?bool $private_car = false;

    #[ORM\Column(type: Types::SMALLINT,nullable: true)]
    private ?int $asistance = null;

    #[ORM\Column(nullable: true)]
    private ?bool $selected = null;

    #[ORM\Column(nullable: true)]
    private ?float $estimatedHours = null;

    #[ORM\Column(nullable: true)]
    private ?float $realHours = null;

    #[ORM\Column(nullable: true)]
    private ?float $extraHours = null;

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

    public function isPrivateCar(): ?bool
    {
        return $this->private_car;
    }

    public function setPrivateCar(bool $private_car): self
    {
        $this->private_car = $private_car;

        return $this;
    }

    public function getAsistance(): ?int
    {
        return $this->asistance;
    }

    public function setAsistance(int $asistance): self
    {
        $this->asistance = $asistance;

        return $this;
    }

    public function isSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(?bool $selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    public function getEstimatedHours(): ?float
    {
        return $this->estimatedHours;
    }

    public function setEstimatedHours(?float $estimatedHours): self
    {
        $this->estimatedHours = $estimatedHours;

        return $this;
    }

    public function getRealHours(): ?float
    {
        return $this->realHours;
    }

    public function setRealHours(?float $realHours): self
    {
        $this->realHours = $realHours;

        return $this;
    }

    public function getExtraHours(): ?float
    {
        return $this->extraHours;
    }

    public function setExtraHours(?float $extraHours): self
    {
        $this->extraHours = $extraHours;

        return $this;
    }
}
