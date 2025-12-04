<?php

namespace App\Entity\Platform\Newsletter;

use App\Entity\Platform\Instance;
use App\Repository\Platform\Newsletter\NewsletterSettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterSettingsRepository::class)]
class NewsletterSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Instance::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Instance $instance;

    #[ORM\Column(length: 255)]
    private ?string $fromName = null;

    #[ORM\Column(length: 255)]
    private ?string $fromEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $defaultSubject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $defaultPlainTextContent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $defaultHtmlContent = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $defaultFooter = null;

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

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(?string $fromName): void
    {
        $this->fromName = $fromName;
    }

    public function getFromEmail(): ?string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(?string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    public function getDefaultSubject(): ?string
    {
        return $this->defaultSubject;
    }

    public function setDefaultSubject(?string $defaultSubject): void
    {
        $this->defaultSubject = $defaultSubject;
    }

    public function getDefaultPlainTextContent(): ?string
    {
        return $this->defaultPlainTextContent;
    }

    public function setDefaultPlainTextContent(?string $defaultPlainTextContent): void
    {
        $this->defaultPlainTextContent = $defaultPlainTextContent;
    }

    public function getDefaultHtmlContent(): ?string
    {
        return $this->defaultHtmlContent;
    }

    public function setDefaultHtmlContent(?string $defaultHtmlContent): void
    {
        $this->defaultHtmlContent = $defaultHtmlContent;
    }

    public function getDefaultFooter(): ?string
    {
        return $this->defaultFooter;
    }

    public function setDefaultFooter(?string $defaultFooter): void
    {
        $this->defaultFooter = $defaultFooter;
    }
}
