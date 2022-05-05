<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConfigCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ConfigCategoryRepository::class)
 */
class ConfigCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\OneToMany(targetEntity=Config::class, mappedBy="config")
     */
    private $category_key;

    /**
     * @ORM\OneToMany(targetEntity=Config::class, mappedBy="configCategory")
     */
    private $configs;

    public function __construct()
    {
        $this->configs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryKey(): ?string
    {
        return $this->category_key;
    }

    public function setCategoryKey(string $category_key): self
    {
        $this->category_key = $category_key;

        return $this;
    }

    /**
     * @return Collection<int, Config>
     */
    public function getConfigs(): Collection
    {
        return $this->configs;
    }

    public function addConfig(Config $config): self
    {
        if (!$this->configs->contains($config)) {
            $this->configs[] = $config;
            $config->setConfigCategory($this);
        }

        return $this;
    }

    public function removeConfig(Config $config): self
    {
        if ($this->configs->removeElement($config)) {
            // set the owning side to null (unless already changed)
            if ($config->getConfigCategory() === $this) {
                $config->setConfigCategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->category_key;
    }
}
