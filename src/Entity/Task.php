<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state_request = null;

    #[ORM\Column(nullable: true)]
    private ?int $extra_time = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Event $Event = null;

    #[ORM\Column(nullable: true)]
    private ?int $breakTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $statusResolveDate = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?bool $state = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Job::class)]
    private Collection $jobs;

    #[ORM\Column(nullable: true)]
    private array $chore = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startTimeCompare = null;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStart_Time(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEnd_Time(?\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getStateRequest(): ?string
    {
        return $this->state_request;
    }

    public function setStateRequest(?string $state_request): self
    {
        $this->state_request = $state_request;

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
        return $this->Event;
    }

    public function setEvent(?Event $Event): self
    {
        $this->Event = $Event;

        return $this;
    }

    public function getBreakTime(): ?int
    {
        return $this->breakTime;
    }

    public function setBreakTime(?int $breakTime): self
    {
        $this->breakTime = $breakTime;

        return $this;
    }

    public function getStatusResolveDate(): ?\DateTimeInterface
    {
        return $this->statusResolveDate;
    }

    public function setStatusResolveDate(?\DateTimeInterface $statusResolveDate): self
    {
        $this->statusResolveDate = $statusResolveDate;

        return $this;
    }
    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getTaskId() === $this) {
                $job->setTaskId(null);
            }
        }

        return $this;
    }
    public function __toString(): string {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getExtraTime(): ?int
    {
        return $this->extra_time;
    }

    public function setExtraTime(?int $extra_time): self
    {
        $this->extra_time = $extra_time;
    }

    public function getChore(): array
    {
        return $this->chore;
    }

    public function setChore(?array $chore): self
    {
        $this->chore = $chore;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(?bool $state): self
    {
        $this->state = $state;

        return $this;
    }
    public function getTotalTime(): int
    {
        $res=0;
        if($this->start_time){
            $res= ($this->end_time->getTimestamp()-$this->start_time->getTimestamp()+$this->extra_time)/(3600);
        }
        return $res;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setTask($this);
        }

        return $this;
    }

    public function getStartTimeCompare(): ?\DateTimeInterface
    {
        return $this->startTimeCompare;
    }

    public function setStartTimeCompare(?\DateTimeInterface $startTimeCompare): self
    {
        $this->startTimeCompare = $startTimeCompare;

        return $this;
    }
}
