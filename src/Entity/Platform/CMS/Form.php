<?php

namespace App\Entity\Platform\CMS;

use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private Instance $instance;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $updatedBy = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'text')]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $code;

    // add notification email
    #[ORM\Column(type: 'text')]
    private string $notificationEmail;

    #[ORM\OneToMany(targetEntity: FormField::class, mappedBy: 'form')]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $fields;

    #[ORM\Column(type: 'text')]
    private string $apiKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNotificationEmail(): string
    {
        return $this->notificationEmail;
    }

    public function setNotificationEmail(string $notificationEmail): self
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->fields = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function setFields(Collection $fields): Form
    {
        $this->fields = $fields;
        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): Form
    {
        $this->apiKey = $apiKey;
        return $this;
    }




    /* CUSTOM */




    public function getShortCode(): string
    {
        return '<pre2>[form id="'.$this->getId().'" name="'.$this->code.'"]</pre2>';
    }
}
