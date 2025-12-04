<?php

namespace App\Entity\Platform\Website;

use App\Entity\Platform\Instance;
use App\Entity\Platform\User;
use App\Repository\Platform\Website\WebsiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebsiteRepository::class)]
class Website
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

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $FTPHost;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $FTPUser;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $FTPPassword;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $FTPPath;

    #[ORM\Column(length: 64, nullable: false)]
    private string $domain;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $name;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $slogan;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: WebsiteMedia::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?WebsiteMedia $favicon = null;

    #[ORM\ManyToOne(targetEntity: WebsiteMedia::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?WebsiteMedia $logo = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phone;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $email;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $address;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $facebook;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $twitter;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $instagram;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $linkedin;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $youtube;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $tiktok;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $theme;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $language;

    #[ORM\Column(length: 16, nullable: true, options: ['default' => 'utf-8'])]
    private ?string $charset;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $title;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $metaDescription;

    #[ORM\Column(length: 320, nullable: true)]
    private ?string $metaKeywords;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $metaAuthor;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $metaRobots;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $headerCSS = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $headerJS = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $headerHTML = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $bodyTopHTML = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $footerJS = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $footerHTML = null;

    public function __construct()
    {
        $this->status = true;
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getFTPHost(): ?string
    {
        return $this->FTPHost;
    }

    public function setFTPHost(?string $FTPHost): self
    {
        $this->FTPHost = $FTPHost;

        return $this;
    }

    public function getFTPUser(): ?string
    {
        return $this->FTPUser;
    }

    public function setFTPUser(?string $FTPUser): self
    {
        $this->FTPUser = $FTPUser;

        return $this;
    }

    public function getFTPPassword(): ?string
    {
        return $this->FTPPassword;
    }

    public function setFTPPassword(?string $FTPPassword): self
    {
        $this->FTPPassword = $FTPPassword;

        return $this;
    }

    public function getFTPPath(): ?string
    {
        return $this->FTPPath;
    }

    public function setFTPPath(?string $FTPPath): self
    {
        $this->FTPPath = $FTPPath;

        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFavicon(): ?WebsiteMedia
    {
        return $this->favicon;
    }

    public function setFavicon(?WebsiteMedia $favicon): self
    {
        $this->favicon = $favicon;

        return $this;
    }

    public function getLogo(): ?WebsiteMedia
    {
        return $this->logo;
    }

    public function setLogo(?WebsiteMedia $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getTiktok(): ?string
    {
        return $this->tiktok;
    }

    public function setTiktok(?string $tiktok): self
    {
        $this->tiktok = $tiktok;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function setCharset(?string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function getMetaAuthor(): ?string
    {
        return $this->metaAuthor;
    }

    public function setMetaAuthor(?string $metaAuthor): self
    {
        $this->metaAuthor = $metaAuthor;

        return $this;
    }

    public function getMetaRobots(): ?string
    {
        return $this->metaRobots;
    }

    public function setMetaRobots(?string $metaRobots): self
    {
        $this->metaRobots = $metaRobots;

        return $this;
    }

    public function getHeaderCSS(): ?string
    {
        return $this->headerCSS;
    }

    public function setHeaderCSS(?string $headerCSS): self
    {
        $this->headerCSS = $headerCSS;

        return $this;
    }

    public function getHeaderJS(): ?string
    {
        return $this->headerJS;
    }

    public function setHeaderJS(?string $headerJS): self
    {
        $this->headerJS = $headerJS;

        return $this;
    }

    public function getHeaderHTML(): ?string
    {
        return $this->headerHTML;
    }

    public function setHeaderHTML(?string $headerHTML): self
    {
        $this->headerHTML = $headerHTML;

        return $this;
    }

    public function getBodyTopHTML(): ?string
    {
        return $this->bodyTopHTML;
    }

    public function setBodyTopHTML(?string $bodyTopHTML): self
    {
        $this->bodyTopHTML = $bodyTopHTML;

        return $this;
    }

    public function getFooterJS(): ?string
    {
        return $this->footerJS;
    }

    public function setFooterJS(?string $footerJS): self
    {
        $this->footerJS = $footerJS;

        return $this;
    }

    public function getFooterHTML(): ?string
    {
        return $this->footerHTML;
    }

    public function setFooterHTML(?string $footerHTML): self
    {
        $this->footerHTML = $footerHTML;

        return $this;
    }
}
