<?php

namespace App\Entity\Platform;

use App\Repository\Platform\BillingProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BillingProfileRepository::class)]
class BillingProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $zip = null;

    #[ORM\Column(length: 64)]
    private ?string $settlement = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $vat = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $euVat = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $billingRegistrationNumber = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $faxNumber = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $email = null;

    // add ManyToMany relationship with Instance entity
    #[ORM\ManyToMany(targetEntity: Instance::class, mappedBy: 'billingProfiles')]
    private Collection $instances;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'billingProfile')]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getZip(): ?int
    {
        return $this->zip;
    }

    public function setZip(int $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getSettlement(): ?string
    {
        return $this->settlement;
    }

    public function setSettlement(string $settlement): static
    {
        $this->settlement = $settlement;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(?string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getEuVat(): ?string
    {
        return $this->euVat;
    }

    public function setEuVat(?string $euVat): static
    {
        $this->euVat = $euVat;

        return $this;
    }

    public function getBillingRegistrationNumber(): ?string
    {
        return $this->billingRegistrationNumber;
    }

    public function setBillingRegistrationNumber(?string $billingRegistrationNumber): static
    {
        $this->billingRegistrationNumber = $billingRegistrationNumber;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFaxNumber(): ?string
    {
        return $this->faxNumber;
    }

    public function setFaxNumber(?string $faxNumber): static
    {
        $this->faxNumber = $faxNumber;

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

    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(Instance $instance): static
    {
        if (!$this->instances->contains($instance)) {
            $this->instances[] = $instance;
        }

        return $this;
    }

    public function removeInstance(Instance $instance): static
    {
        $this->instances->removeElement($instance);

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
            $order->setBillingProfile($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getBillingProfile() === $this) {
                $order->setBillingProfile(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName(); // or any other property you want to use as a string representation
    }
}
