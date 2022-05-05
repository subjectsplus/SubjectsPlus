<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $option_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $option_label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $option_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autoload;

//    /**
//     * @ORM\ManyToOne(targetEntity=ConfigCategory::class, inversedBy="configs")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=ConfigCategory::class, inversedBy="configs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionKey(): ?string
    {
        return $this->option_key;
    }

    public function setOptionKey(string $option_key): self
    {
        $this->option_key = $option_key;

        return $this;
    }

    public function getOptionLabel(): ?string
    {
        return $this->option_label;
    }

    public function setOptionLabel(string $option_label): self
    {
        $this->option_label = $option_label;

        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->option_value;
    }

    public function setOptionValue(string $option_value): self
    {
        $this->option_value = $option_value;

        return $this;
    }

    public function getAutoload(): ?bool
    {
        return $this->autoload;
    }

    public function setAutoload(bool $autoload): self
    {
        $this->autoload = $autoload;

        return $this;
    }

//    public function getCategory(): ?ConfigCategory
//    {
//        return $this->category;
//    }
//
//    public function setCategory(?ConfigCategory $configCategory): self
//    {
//        $this->category = $configCategory;
//
//        return $this;
//    }

    public function getConfigCategory(): ?ConfigCategory
    {
        return $this->configCategory;
    }

    public function setConfigCategory(?ConfigCategory $configCategory): self
    {
        $this->configCategory = $configCategory;

        return $this;
    }
}
