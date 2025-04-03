<?php

namespace App\Entity\Platform\Newsletter;

use App\Entity\Platform\Instance;
use App\Enum\NewsletterStatusEnum;
use App\Repository\Platform\Newsletter\NewsletterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $plainTextContent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $htmlContent = null;

    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'newsletters')]
    #[ORM\JoinColumn(nullable: false)]
    private Instance $instance;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $sendAt = null;

    #[ORM\Column(type: 'string', enumType: NewsletterStatusEnum::class)]
    private NewsletterStatusEnum $status = NewsletterStatusEnum::DRAFT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getPlainTextContent(): ?string
    {
        return $this->plainTextContent;
    }

    public function setPlainTextContent(?string $plainTextContent): static
    {
        $this->plainTextContent = $plainTextContent;

        return $this;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    public function setHtmlContent(?string $htmlContent): static
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): static
    {
        $this->instance = $instance;

        return $this;
    }

    public function getSendAt(): ?\DateTimeInterface
    {
        return $this->sendAt;
    }

    public function setSendAt(?\DateTimeInterface $sendAt): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function getStatus(): NewsletterStatusEnum|string
    {
        return $this->status;
    }

    public function setStatus(NewsletterStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isSent(): bool
    {
        return $this->status === NewsletterStatusEnum::SENT;
    }

    public function isScheduled(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED;
    }

    public function isDraft(): bool
    {
        return $this->status === NewsletterStatusEnum::DRAFT;
    }

    public function isReadyToSend(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= new \DateTime();
    }

    public function isReadyToSendAt(\DateTimeInterface $date): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= $date;
    }

    public function isReadyToSendNow(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= new \DateTime();
    }

    public function isReadyToSendAtNow(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= new \DateTime();
    }

    public function isReadyToSendAtDate(\DateTimeInterface $date): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= $date;
    }

    public function isReadyToSendAtDateTime(\DateTimeInterface $date): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= $date;
    }

    public function isReadyToSendAtDateTimeNow(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt <= new \DateTime();
    }

    public function isReadyToSendAtDateTimeNowOrLater(): bool
    {
        return $this->status === NewsletterStatusEnum::SCHEDULED && $this->sendAt >= new \DateTime();
    }
}
