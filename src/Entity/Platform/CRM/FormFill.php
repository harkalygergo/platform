<?php

namespace App\Entity\Platform\CRM;

use App\Entity\Platform\Instance;
use App\Entity\Platform\Interface\TimestampableInterface;
use App\Entity\Platform\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FormFill implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Instance::class)]
    private Instance $instance;

    #[ORM\ManyToOne(targetEntity: Form::class, inversedBy: 'fields')]
    private Form $form;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(type: 'json')]
    private array $data = [];

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstance(): Instance
    {
        return $this->instance;
    }

    public function setInstance(Instance $instance): FormFill
    {
        $this->instance = $instance;
        return $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function setForm(Form $form): FormFill
    {
        $this->form = $form;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): FormFill
    {
        $this->ip = $ip;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): FormFill
    {
        $this->data = $data;

        return $this;
    }
}
