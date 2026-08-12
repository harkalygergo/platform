<?php

namespace App\Entity\Platform\CRM;

use App\Entity\Platform\Client;
use App\Entity\Platform\Instance;
use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\Trait\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Customer implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isActive = true;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastLogin = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $billingCountry = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $billingZip = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $billingSettlement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingAddress = null;
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $shippingCountry = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $shippingZip = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $shippingSettlement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shippingAddress = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Instance $instance = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $source = null;

    // add 1:1 Client connection, but not required}
    #[ORM\OneToOne(
        targetEntity: Client::class,
        inversedBy: 'Customer',
        cascade: ['persist']
    )]
    #[ORM\JoinColumn(
        name: 'client_id',
        referencedColumnName: 'id',
        nullable: true,
        onDelete: 'SET NULL'
    )]
    private ?Client $client = null;

    public function getBillingCountry(): ?string
    {
        return $this->billingCountry;
    }

    public function setBillingCountry(?string $billingCountry): Customer
    {
        $this->billingCountry = $billingCountry;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): Customer
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): Customer
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): Customer
    {
        $this->password = $password;
        return $this;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): Customer
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): Customer
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): Customer
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Customer
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    public function getBillingZip(): ?string
    {
        return $this->billingZip;
    }

    public function setBillingZip(?string $billingZip): Customer
    {
        $this->billingZip = $billingZip;
        return $this;
    }

    public function getBillingSettlement(): ?string
    {
        return $this->billingSettlement;
    }

    public function setBillingSettlement(?string $billingSettlement): Customer
    {
        $this->billingSettlement = $billingSettlement;
        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): Customer
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    public function getShippingCountry(): ?string
    {
        return $this->shippingCountry;
    }

    public function setShippingCountry(?string $shippingCountry): Customer
    {
        $this->shippingCountry = $shippingCountry;
        return $this;
    }

    public function getShippingZip(): ?string
    {
        return $this->shippingZip;
    }

    public function setShippingZip(?string $shippingZip): Customer
    {
        $this->shippingZip = $shippingZip;
        return $this;
    }

    public function getShippingSettlement(): ?string
    {
        return $this->shippingSettlement;
    }

    public function setShippingSettlement(?string $shippingSettlement): Customer
    {
        $this->shippingSettlement = $shippingSettlement;
        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): Customer
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    public function setInstance(?Instance $instance): Customer
    {
        $this->instance = $instance;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): Customer
    {
        $this->source = $source;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): Customer
    {
        $this->client = $client;
        return $this;
    }
}
