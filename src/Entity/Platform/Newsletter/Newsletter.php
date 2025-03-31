<?php

namespace App\Entity\Platform\Newsletter;

use App\Entity\Platform\Instance;
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

    public function isSent(): bool
    {
        return $this->sendAt !== null;
    }

    public function isScheduled(): bool
    {
        return $this->sendAt !== null && $this->sendAt > new \DateTime();
    }

    public function isReadyToSend(): bool
    {
        return $this->sendAt !== null && $this->sendAt <= new \DateTime();
    }

    public function getSendAtFormatted(): ?string
    {
        return $this->sendAt ? $this->sendAt->format('Y-m-d H:i:s') : null;
    }

    public function setSendAtFormatted(?string $sendAt): static
    {
        if ($sendAt) {
            $this->sendAt = \DateTime::createFromFormat('Y-m-d H:i:s', $sendAt);
        } else {
            $this->sendAt = null;
        }

        return $this;
    }

    public function getSendAtFormattedForForm(): ?string
    {
        return $this->sendAt ? $this->sendAt->format('Y-m-d\TH:i') : null;
    }

    public function setSendAtFormattedForForm(?string $sendAt): static
    {
        if ($sendAt) {
            $this->sendAt = \DateTime::createFromFormat('Y-m-d\TH:i', $sendAt);
        } else {
            $this->sendAt = null;
        }

        return $this;
    }

    public function getSendAtFormattedForDisplay(): ?string
    {
        return $this->sendAt ? $this->sendAt->format('d/m/Y H:i') : null;
    }

    public function setSendAtFormattedForDisplay(?string $sendAt): static
    {
        if ($sendAt) {
            $this->sendAt = \DateTime::createFromFormat('d/m/Y H:i', $sendAt);
        } else {
            $this->sendAt = null;
        }

        return $this;
    }

    public function getSendAtFormattedForDisplayWithTimezone(): ?string
    {
        return $this->sendAt ? $this->sendAt->setTimezone(new \DateTimeZone('Europe/Paris'))->format('d/m/Y H:i') : null;
    }

    public function setSendAtFormattedForDisplayWithTimezone(?string $sendAt): static
    {
        if ($sendAt) {
            $this->sendAt = \DateTime::createFromFormat('d/m/Y H:i', $sendAt, new \DateTimeZone('Europe/Paris'));
        } else {
            $this->sendAt = null;
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->subject;
    }
}
