<?php

namespace App\Entity\Platform\Order;

use App\Entity\Platform\Instance;
use App\Enum\Platform\OrderStatusEnum;
use App\Repository\Platform\Order\OrderEmailTemplateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderEmailTemplateRepository::class)]
class OrderEmailTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Instance $instance;

    #[ORM\Column(length: 50, enumType: OrderStatusEnum::class)]
    private OrderStatusEnum $orderStatus;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $plainTextContent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $htmlContent = null;

    #[ORM\Column]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): void
    {
        $this->instance = $instance;
    }

    public function getOrderStatus(): OrderStatusEnum
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(OrderStatusEnum $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getPlainTextContent(): ?string
    {
        return $this->plainTextContent;
    }

    public function setPlainTextContent(?string $plainTextContent): void
    {
        $this->plainTextContent = $plainTextContent;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    public function setHtmlContent(?string $htmlContent): void
    {
        $this->htmlContent = $htmlContent;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getName(): string
    {
        return $this->orderStatus->label();
    }
}
