<?php

namespace App\Entity\Platform;

use App\Entity\Platform\Newsletter\Newsletter;
use App\Entity\Platform\Popup\Popup;
use App\Repository\Platform\InstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstanceRepository::class)]
class Instance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'boolean')]
    private int $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intranet = null;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => 'Platform'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'instances')]
    private Collection $users;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ownInstances')]
    #[ORM\JoinColumn(nullable: true, options: ['default' => null])]
    private ?User $owner = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'instance', orphanRemoval: true, cascade: ['persist'])]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private Collection $services;

    #[ORM\ManyToMany(targetEntity: BillingProfile::class, inversedBy: 'instances')]
    private Collection $billingProfiles;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'instance')]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: Newsletter::class, mappedBy: 'instance')]
    private Collection $newsletters;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'instance')]
    private Collection $clients;

    #[ORM\OneToMany(targetEntity: Popup::class, mappedBy: 'instance')]
    private Collection $popups;

    public function __construct()
    {
        $this->status = true;
        $this->createdAt = new \DateTimeImmutable();
        $this->users = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->billingProfiles = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->popups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIntranet(): ?string
    {
        return $this->intranet;
    }

    public function setIntranet(?string $intranet): static
    {
        $this->intranet = $intranet;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addInstance($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeInstance($this);
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
    {
        $this->owner = $owner;
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    public function setServices(Collection $services): void
    {
        $this->services = $services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setInstance($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getInstance() === $this) {
                $service->setInstance(null);
            }
        }

        return $this;
    }

    public function getBillingProfiles(): Collection
    {
        return $this->billingProfiles;
    }

    public function addBillingProfile(BillingProfile $billingProfile): self
    {
        if (!$this->billingProfiles->contains($billingProfile)) {
            $this->billingProfiles->add($billingProfile);
            $billingProfile->addInstance($this);
        }

        return $this;
    }

    public function removeBillingProfile(BillingProfile $billingProfile): self
    {
        if ($this->billingProfiles->removeElement($billingProfile)) {
            $billingProfile->removeInstance($this);
        }

        return $this;
    }

    public function setBillingProfiles(Collection $billingProfiles): void
    {
        $this->billingProfiles = $billingProfiles;
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
            $order->setInstance($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getInstance() === $this) {
                $order->setInstance(null);
            }
        }

        return $this;
    }

    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters->add($newsletter);
            $newsletter->setInstance($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): self
    {
        if ($this->newsletters->removeElement($newsletter)) {
            // set the owning side to null (unless already changed)
            if ($newsletter->getInstance() === $this) {
                $newsletter->setInstance(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setInstance($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getInstance() === $this) {
                $client->setInstance(null);
            }
        }

        return $this;
    }

    public function getPopups(): Collection
    {
        return $this->popups;
    }

    public function addPopup(Popup $popup): self
    {
        if (!$this->popups->contains($popup)) {
            $this->popups->add($popup);
            $popup->setInstance($this);
        }

        return $this;
    }

    public function removePopup(Popup $popup): self
    {
        if ($this->popups->removeElement($popup)) {
            // set the owning side to null (unless already changed)
            if ($popup->getInstance() === $this) {
                //$popup->setInstance(null);
            }
        }

        return $this;
    }

    public function setPopups(Collection $popups): void
    {
        $this->popups = $popups;
    }
}
