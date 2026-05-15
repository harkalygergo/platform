<?php

namespace App\Entity\Platform\CMS;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class VisitorLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $visitedAt;

    #[ORM\Column(length: 2048)]
    private string $url;

    #[ORM\Column(nullable: true)]
    private ?string $referrer = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(nullable: true)]
    private ?string $source = null;

    #[ORM\Column(nullable: true)]
    private ?string $sessionId = null;

    #[ORM\Column(nullable: true)]
    private ?string $visitorId = null;

    #[ORM\Column(nullable: true)]
    private ?string $route = null;

    #[ORM\Column(nullable: true)]
    private ?string $contentType = null;

    #[ORM\Column(nullable: true)]
    private ?int $contentId = null;

    public function getVisitorId(): ?string
    {
        return $this->visitorId;
    }

    public function setVisitorId(?string $visitorId): void
    {
        $this->visitorId = $visitorId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): \DateTimeImmutable
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeImmutable $visitedAt): void
    {
        $this->visitedAt = $visitedAt;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(?string $referrer): void
    {
        $this->referrer = $referrer;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(?string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): void
    {
        $this->route = $route;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    public function setContentId(?int $contentId): void
    {
        $this->contentId = $contentId;
    }
}
