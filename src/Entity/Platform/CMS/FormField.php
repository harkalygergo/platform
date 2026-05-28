<?php

namespace App\Entity\Platform\CMS;

use App\Entity\Platform\Instance;
use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\Trait\TimestampableTrait;
use App\Repository\Platform\CMS\FormFieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormFieldRepository::class)]
class FormField implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private Instance $instance;

    #[ORM\ManyToOne(targetEntity: Form::class)]
    private Form $form;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column]
    private ?bool $isRequired = null;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => null])]
    private ?string $defaultValue = null;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => null])]
    private ?string $placeholder = null;

    #[ORM\Column(length: 255, nullable: true, options: ['default' => null])]
    private ?string $helpText = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): FormField
    {
        $this->position = $position;
        return $this;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): FormField
    {
        $this->instance = $instance;
        return $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function setForm(Form $form): FormField
    {
        $this->form = $form;
        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): FormField
    {
        $this->status = $status;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): FormField
    {
        $this->name = $name;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): FormField
    {
        $this->label = $label;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): FormField
    {
        $this->type = $type;
        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(?bool $isRequired): FormField
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): FormField
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(?string $placeholder): FormField
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function getHelpText(): ?string
    {
        return $this->helpText;
    }

    public function setHelpText(?string $helpText): FormField
    {
        $this->helpText = $helpText;
        return $this;
    }
}
