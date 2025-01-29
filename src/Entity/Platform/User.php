<?php

namespace App\Entity\Platform;

use App\Repository\Platform\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';
    final public const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $namePrefix = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $middleName = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $nickName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $birthName = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $position = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastLogin = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastActivation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImageUrl = null;

    #[ORM\ManyToMany(targetEntity: Instance::class, mappedBy: 'users')]
    private Collection $instances;

    /**
     * @var Collection<int, Instance>
     */
    #[ORM\OneToMany(targetEntity: Instance::class, mappedBy: 'owner', orphanRemoval: true, cascade: ['persist'])]
    private Collection $ownInstances;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private ?Instance $defaultInstance;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Cart $cart = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'createdBy')]
    private Collection $orders;

    public function __construct()
    {
        $this->instances = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        $this->roles[] = $role;

        return $this;
    }

    public function removeRole(string $role): static
    {
        $key = array_search($role, $this->roles, true);
        if ($key !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function setNamePrefix(?string $namePrefix): static
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): static
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(?string $nickName): static
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): static
    {
        $this->birthName = $birthName;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLastActivation(): ?\DateTimeImmutable
    {
        return $this->lastActivation;
    }

    public function setLastActivation(?\DateTimeImmutable $lastActivation): static
    {
        $this->lastActivation = $lastActivation;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getProfileImageUrl(): ?string
    {
        return $this->profileImageUrl;
    }

    public function setProfileImageUrl(?string $profileImageUrl): static
    {
        $this->profileImageUrl = $profileImageUrl;

        return $this;
    }

    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(Instance $instance): self
    {
        if (!$this->instances->contains($instance)) {
            $this->instances->add($instance);
            $instance->addUser($this);
        }

        return $this;
    }

    public function removeInstance(Instance $instance): self
    {
        if ($this->instances->removeElement($instance)) {
            $instance->removeUser($this);
        }

        return $this;
    }

    /*
    public function getDefaultInstance()
    {
        return $this->instances->first();
    }

    public function setDefaultInstance($instance): void
    {
        $this->instances->add($instance);
    }
    */

    public function getOwnInstances(): Collection
    {
        return $this->ownInstances;
    }

    public function setOwnInstances(Collection $ownInstances): void
    {
        $this->ownInstances = $ownInstances;
    }

    public function addOwnInstance(Instance $instance): self
    {
        if (!$this->ownInstances->contains($instance)) {
            $this->ownInstances->add($instance);
            $instance->setOwner($this);
        }

        return $this;
    }

    public function removeOwnInstance(Instance $instance): self
    {
        if ($this->ownInstances->removeElement($instance)) {
            // set the owning side to null (unless already changed)
            if ($instance->getOwner() === $this) {
                $instance->setOwner(null);
            }
        }

        return $this;
    }

    public function getDefaultInstance(): ?Instance
    {
        return $this->defaultInstance;
    }

    public function setDefaultInstance(?Instance $defaultInstance): self
    {
        $this->defaultInstance = $defaultInstance;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return (string) $this->email;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCreatedBy($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCreatedBy() === $this) {
                $order->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getFullName()
    {
        return $this->namePrefix . ' ' . $this->lastName . ' ' . $this->middleName . ' ' . $this->firstName;
    }

    public function __toString(): string
    {
        return $this->getFullName(); // or any other property you want to use as a string representation
    }
}
