<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faqpage.
 *
 * @ORM\Table(name="faqpage")
 * @ORM\Entity
 */
class Faqpage
{
    /**
     * @var int
     *
     * @ORM\Column(name="faqpage_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $faqpageId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    public function getFaqpageId(): ?int
    {
        return $this->faqpageId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
