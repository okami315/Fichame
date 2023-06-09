<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $dni = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $regDate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\Column]
    private ?int $monthlytime = null;

    #[ORM\Column(nullable: true)]
    private ?int $remaininghours = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $naf = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserEvent::class)]
    private Collection $userEvents;

    #[ORM\Column(nullable: true)]
    private ?bool $driver = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Signing::class)]
    private Collection $signings;

    #[ORM\Column(nullable: true)]
    private ?float $fixed_hours = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $agreement = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    private ?string $social_security = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this -> regDate = new \DateTime();
        $this->events = new ArrayCollection();
        $this->userEvents = new ArrayCollection();
        $this->signings = new ArrayCollection();
        $this->birthdate = new \DateTime();
        $this->calculateAge();
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        $this->calculateAge();

        return $this;
    }

    private function calculateAge(): void
    {
        $now = new \DateTime();
        $this->age = $now->diff($this->birthdate)->y;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getRegDate(): ?\DateTimeInterface
    {
        return $this->regDate;
    }

    public function setRegDate(\DateTimeInterface $regDate): self
    {
        $this->regDate = $regDate;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function __toString()
    {
        return $this->fullName;
    }

    public function getMonthlytime(): ?int
    {
        return $this->monthlytime;
    }

    public function setMonthlytime(int $monthlytime): int
    {
        return $this->monthlytime = $monthlytime;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getRemaininghours(): ?int
    {
        return $this->remaininghours;
    }

    public function setRemaininghours(?int $remaininghours): self
    {
        return $this->remaininghours += $remaininghours;
    }
        
    public function getNaf(): ?string
    {
        return $this->naf;
    }

    public function setNaf(?string $naf): self
    {
        $this->naf = $naf;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }
    
    public function changeRemainingHours(){
        if($this->lastLogin->format('m') != (new \Datetime('now'))->format('m')){
            if((new \Datetime('now'))->format('m')=='01'){
                $this->setRemaininghours($this->monthlytime);
            }else{
                $this->setRemaininghours(((new \Datetime('2023-03-12'))->format('m')-$this->lastLogin->format('m'))*$this->monthlytime);
            }
        }
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
            $userEvent->setUser($this);
        }

        return $this;
    }

    public function removeUserEvent(UserEvent $userEvent): self
    {
        if ($this->userEvents->removeElement($userEvent)) {
            // set the owning side to null (unless already changed)
            if ($userEvent->getUser() === $this) {
                $userEvent->setUser(null);
            }
        }

        return $this;
    }

    public function isDriver(): ?bool
    {
        return $this->driver;
    }

    public function setDriver(?bool $driver): self
    {
        $this->driver = $driver;

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
            $signing->setUser($this);
        }

        return $this;
    }

    public function removeSigning(Signing $signing): self
    {
        if ($this->signings->removeElement($signing)) {
            // set the owning side to null (unless already changed)
            if ($signing->getUser() === $this) {
                $signing->setUser(null);
            }
        }

        return $this;
    }

    public function getFixedHours(): ?float
    {
        return $this->fixed_hours;
    }

    public function setFixedHours(?float $fixed_hours): self
    {
        $this->fixed_hours = $fixed_hours;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAgreement(): ?int
    {
        return $this->agreement;
    }

    public function setAgreement(?int $agreement): self
    {
        $this->agreement = $agreement;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getSocialSecurity(): ?string
    {
        return $this->social_security;
    }

    public function setSocialSecurity(string $social_security): self
    {
        $this->social_security = $social_security;

        return $this;
    }
}
