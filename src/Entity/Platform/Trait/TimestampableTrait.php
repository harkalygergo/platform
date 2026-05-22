<?php

namespace App\Entity\Platform\Trait;

use App\Entity\Platform\User;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $updatedBy = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getCreatedBy(): User { return $this->createdBy; }
    public function setCreatedBy(User $user): void { $this->createdBy = $user; }

    public function getUpdatedBy(): ?User { return $this->updatedBy; }
    public function setUpdatedBy(?User $user): void { $this->updatedBy = $user; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $dt): void { $this->createdAt = $dt; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $dt): void { $this->updatedAt = $dt; }
}
