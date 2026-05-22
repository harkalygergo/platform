<?php

namespace App\Entity\Platform\CMS;

use App\Entity\Platform\Instance;
use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\Trait\TimestampableTrait;
use App\Enum\Platform\WidgetTypeEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Widget implements TimestampableInterface  // ← marker interface
{
    use TimestampableTrait;                   // ← fields + getters/setters

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Instance::class, inversedBy: 'widgets')]
    private Instance $instance;

    #[ORM\Column(type: 'boolean')]
    private bool $status;

    #[ORM\Column(enumType: WidgetTypeEnum::class)]
    private WidgetTypeEnum $templateCode;

    #[ORM\Column(type: 'text')]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return Widget
     */
    public function setContent(?string $content): Widget
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Widget
     */
    public function setDescription(?string $description): Widget
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Widget
     */
    public function setName(string $name): Widget
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return WidgetTypeEnum
     */
    public function getTemplateCode(): WidgetTypeEnum
    {
        return $this->templateCode;
    }

    public function templateCodeLabel(): string
    {
        return $this->templateCode->label();
    }

    /**
     * @param WidgetTypeEnum $templateCode
     * @return Widget
     */
    public function setTemplateCode(WidgetTypeEnum $templateCode): Widget
    {
        $this->templateCode = $templateCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return Widget
     */
    public function setStatus(bool $status): Widget
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Instance
     */
    public function getInstance(): Instance
    {
        return $this->instance;
    }

    /**
     * @param Instance $instance
     * @return Widget
     */
    public function setInstance(Instance $instance): Widget
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
