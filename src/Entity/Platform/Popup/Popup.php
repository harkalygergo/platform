<?php

namespace App\Entity\Platform\Popup;

use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use App\Repository\Platform\PopupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PopupRepository::class)]
class Popup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'popups')]
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
    private string $modalTitle;

    #[ORM\Column(type: 'text')]
    private string $modalBody;

    #[ORM\Column(type: 'text')]
    private string $modalFooter;

    #[ORM\Column(type: 'integer')]
    private int $maximumAppearance;

    #[ORM\Column(type: 'integer')]
    private int $shownCount;

    #[ORM\Column(type: 'text')]
    private string $css;

    #[ORM\Column(type: 'text')]
    private string $js;

    public function __construct()
    {
        $this->status = true;
        $this->shownCount = 0;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): void
    {
        $this->instance = $instance;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getUpdatedBy(): User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(User $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getModalBody(): string
    {
        return $this->modalBody;
    }

    public function setModalBody(string $modalBody): void
    {
        $this->modalBody = $modalBody;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getModalTitle(): string
    {
        return $this->modalTitle;
    }

    public function setModalTitle(string $modalTitle): void
    {
        $this->modalTitle = $modalTitle;
    }

    public function getModalFooter(): string
    {
        return $this->modalFooter;
    }

    public function setModalFooter(string $modalFooter): void
    {
        $this->modalFooter = $modalFooter;
    }

    public function getMaximumAppearance(): int
    {
        return $this->maximumAppearance;
    }

    public function setMaximumAppearance(int $maximumAppearance): void
    {
        $this->maximumAppearance = $maximumAppearance;
    }

    public function getShownCount(): int
    {
        return $this->shownCount;
    }

    public function setShownCount(int $shownCount): void
    {
        $this->shownCount = $shownCount;
    }

    public function getCss(): string
    {
        return $this->css;
    }

    public function setCss(string $css): void
    {
        $this->css = $css;
    }

    public function getJs(): string
    {
        return $this->js;
    }

    public function setJs(string $js): void
    {
        $this->js = $js;
    }
}
