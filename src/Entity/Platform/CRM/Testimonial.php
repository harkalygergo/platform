<?php

namespace App\Entity\Platform\CRM;

use App\Entity\Platform\Instance;
use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\Media\Media;
use App\Entity\Platform\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Testimonial implements TimestampableInterface  // ← marker interface
{
    use TimestampableTrait;                   // ← fields + getters/setters

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private Instance $instance;

    #[ORM\Column(type: 'boolean')]
    private bool $status;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: "main_image_id", referencedColumnName: "id", nullable: true)]
    private ?Media $mainImage = null;

    #[ORM\Column(type: 'text')]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): Testimonial
    {
        $this->instance = $instance;
        return $this;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): Testimonial
    {
        $this->status = $status;
        return $this;
    }

    public function getMainImage(): ?Media
    {
        return $this->mainImage;
    }

    public function setMainImage(?Media $mainImage): Testimonial
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $name): Testimonial
    {
        $this->title = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Testimonial
    {
        $this->description = $description;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Testimonial
    {
        $this->content = $content;
        return $this;
    }
}
