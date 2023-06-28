<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $schedule = null;
    
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

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = 0;

    #[ORM\Column]
    private ?int $workers_available = 0;

    #[ORM\Column]
    private ?float $distance = null;

    #[ORM\Column]
    private ?int $drivers_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $editDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Signing::class)]
    private Collection $signings;

    #[ORM\Column(nullable: true)]
    private ?float $estimated_hours = null;

    #[ORM\Column(nullable: true)]
    private ?int $pending_workers = null;

    #[ORM\Column(nullable: true)]
    private ?int $drivers_available = null;

    #[ORM\Column(nullable: true)]
    private ?int $workers_selected = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;
    

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->userEvents = new ArrayCollection();
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime();
        $this->signings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId($id){
        return $this;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getWorkersAvailable(): ?int
    {
        return $this->workers_available;
    }

    public function setWorkersAvailable(int $workers_available): self
    {
        $this->workers_available = $workers_available;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDriversNumber(): ?int
    {
        return $this->drivers_number;
    }

    public function setDriversNumber(int $drivers_number): self
    {
        $this->drivers_number = $drivers_number;

        return $this;
    }

    public function getEditDate(): ?\DateTimeInterface
    {
        return $this->editDate;
    }

    public function setEditDate(?\DateTimeInterface $editDate): self
    {
        $this->editDate = $editDate;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getSignings(): Collection
    {
        return $this->signings;
    }

    public function addSigning(Signing $signing): self
    {
        if (!$this->signings->contains($signing)) {
            $this->signings->add($signing);
            $signing->setEvent($this);
        }

        return $this;
    }

    public function removeSigning(Signing $signing): self
    {
        if ($this->signings->removeElement($signing)) {
            // set the owning side to null (unless already changed)
            if ($signing->getEvent() === $this) {
                $signing->setEvent(null);
            }
        }

        return $this;
    }

    public function getEstimatedHours(): ?float
    {
        return $this->estimated_hours;
    }

    public function setEstimatedHours(?float $estimated_hours): self
    {
        $this->estimated_hours = $estimated_hours;

        return $this;
    }

    public function getPendingWorkers(): ?int
    {
        return $this->pending_workers;
    }

    public function setPendingWorkers(?int $pending_workers): self
    {
        $this->pending_workers = $pending_workers;

        return $this;
    }

    public function getDriversAvailable(): ?int
    {
        return $this->drivers_available;
    }

    public function setDriversAvailable(?int $drivers_available): self
    {
        $this->drivers_available = $drivers_available;

        return $this;
    }

    public function getWorkersSelected(): ?int
    {
        return $this->workers_selected;
    }

    public function setWorkersSelected(?int $workers_selected): self
    {
        $this->workers_selected = $workers_selected;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
