<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\Column(length: 255)]
    private ?string $schedule = null;

    #[ORM\Column(length: 255)]
    private ?string $linkInformation = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkForm = null;

    #[ORM\Column]
    private ?int $workers_number = null;

    #[ORM\OneToMany(mappedBy: 'Event', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'events')]
    private Collection $type;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: UserEvent::class)]
    private Collection $userEvents;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userEvents = new ArrayCollection();
    }

    public function getId(): ?int
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


    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getLinkInformation(): ?string
    {
        return $this->linkInformation;
    }

    public function setLinkInformation(string $linkInformation): self
    {
        $this->linkInformation = $linkInformation;

        return $this;
    }


    public function getLinkForm(): ?string
    {
        return $this->linkForm;
    }

    public function setLinkForm(?string $linkForm): self
    {
        $this->linkForm = $linkForm;

        return $this;
    }

    public function getWorkersNumber(): ?int
    {
        return $this->workers_number;
    }

    public function setWorkersNumber(int $workers_number): self
    {
        $this->workers_number = $workers_number;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setEvent($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getEvent() === $this) {
                $task->setEvent(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->type->removeElement($type);

        return $this;
    }

    /**
     * @return Collection<int, UserEvent>
     */
    public function getUserEvents(): Collection
    {
        return $this->userEvents;
    }

    public function addUserEvent(UserEvent $userEvent): self
    {
        if (!$this->userEvents->contains($userEvent)) {
            $this->userEvents->add($userEvent);
            $userEvent->setEvent($this);
        }

        return $this;
    }

    public function removeUserEvent(UserEvent $userEvent): self
    {
        if ($this->userEvents->removeElement($userEvent)) {
            // set the owning side to null (unless already changed)
            if ($userEvent->getEvent() === $this) {
                $userEvent->setEvent(null);
            }
        }

        return $this;
    }
}
