<?php

namespace App\Entity\Platform;

use App\Repository\Platform\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // We can use constants for roles to find usages all over the application rather
    // than doing a full-text search on the "ROLE_" string.
    // It also prevents from making typo errors.
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'user')]
    private Collection $services;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $language = null;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(name: "default_instance_id")]
    private ?Instance $defaultInstance;

    // add instances many to many
    #[ORM\ManyToMany(targetEntity: Instance::class, mappedBy: 'users')]
    //#[ORM\JoinTable(name: 'user_instance')]
    private Collection $instances;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $position = null;

    // add boolean status, default true
    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    private bool $status = true;

    /**
     * @var string[]
     */
    //#[ORM\Column(type: Types::JSON, options: ["default" => '["'.self::ROLE_USER.'"]'])]
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    // last login defaults to 1970-01-01 00:00:00
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "1970-01-01 00:00:00"])]
    private \DateTime $lastLogin;

    #[ORM\ManyToMany(targetEntity: BillingProfile::class, mappedBy: 'User')]
    private Collection $billingProfiles;

    // add profile image url, default null
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImageUrl = null;

    // add favourites as collection of route names
    #[ORM\Column(type: Types::JSON, nullable: true, options: ["default" => null])]
    private ?array $favourites = null;

    // add UserFiles
    #[ORM\OneToMany(targetEntity: UserFile::class, mappedBy: 'user')]
    private Collection $files;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->billingProfiles = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function getDefaultInstance(): ?Instance
    {
        return $this->defaultInstance;
    }

    public function setDefaultInstance(?Instance $defaultInstance): void
    {
        $this->defaultInstance = $defaultInstance;
    }

    /**
     * @return Collection<int, Instance>
     */
    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(Instance $instance): static
    {
        if (!$this->instances->contains($instance)) {
            $this->instances->add($instance);
            $instance->addUser($this);
        }

        return $this;
    }

    public function removeInstance(Instance $instance): static
    {
        if ($this->instances->removeElement($instance)) {
            $instance->removeUser($this);
        }

        return $this;
    }

    public function setInstances(Collection $instances): static
    {
        $this->instances = $instances;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getLastLogin(): \DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return Collection<int, BillingProfile>
     */
    public function getBillingProfiles(): Collection
    {
        return $this->billingProfiles;
    }

    public function addBillingProfile(BillingProfile $billingProfile): static
    {
        if (!$this->billingProfiles->contains($billingProfile)) {
            $this->billingProfiles->add($billingProfile);
            $billingProfile->addUser($this);
        }

        return $this;
    }

    public function removeBillingProfile(BillingProfile $billingProfile): static
    {
        if ($this->billingProfiles->removeElement($billingProfile)) {
            $billingProfile->removeUser($this);
        }

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

    public function getFavourites(): ?array
    {
        return $this->favourites;
    }

    public function setFavourites(?array $favourites): static
    {
        $this->favourites = $favourites;

        return $this;
    }

    public function addFavourite(string $favourite): static
    {
        if (!in_array($favourite, $this->favourites)) {
            $this->favourites[] = $favourite;
        }

        return $this;
    }

    public function removeFavourite(string $favourite): static
    {
        if (in_array($favourite, $this->favourites)) {
            $key = array_search($favourite, $this->favourites);
            unset($this->favourites[$key]);
        }

        return $this;
    }

    public function hasFavourite(string $favourite): bool
    {
        return in_array($favourite, $this->favourites);
    }

    public function clearFavourites(): static
    {
        $this->favourites = [];

        return $this;
    }

    public function getFavouritesCount(): int
    {
        return count($this->favourites);
    }

    public function isFavouritesEmpty(): bool
    {
        return empty($this->favourites);
    }

    public function getFavouritesAsString(): string
    {
        return implode(',', $this->favourites);
    }

    public function setFavouritesFromString(string $favourites): static
    {
        $this->favourites = explode(',', $favourites);

        return $this;
    }

    /**
     * @return Collection<int, UserFile>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(UserFile $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setUser($this);
        }

        return $this;
    }

    public function removeFile(UserFile $file): static
    {
        if ($this->files->removeElement($file)) {
            $file->setUser(null);
        }

        return $this;
    }

    public function setFiles(Collection $files): static
    {
        $this->files = $files;

        return $this;
    }

    public function clearFiles(): static
    {
        $this->files->clear();

        return $this;
    }
}
