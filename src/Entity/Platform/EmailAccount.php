<?php

namespace App\Entity\Platform;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EmailAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'emailAccounts')]
    private Instance $instance;

    // TODO Service -> Domain
    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'emailAccounts')]
    private Service $service;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private $prefix;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $updatedBy = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getInstance(): \App\Entity\Platform\Instance
    {
        return $this->instance;
    }

    public function setInstance(\App\Entity\Platform\Instance $instance): void
    {
        $this->instance = $instance;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function setService(Service $service): void
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getCreatedBy(): \App\Entity\Platform\User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(\App\Entity\Platform\User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getUpdatedBy(): ?\App\Entity\Platform\User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?\App\Entity\Platform\User $updatedBy): void
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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
