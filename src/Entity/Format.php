<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Format.
 *
 * @ORM\Table(name="format")
 * @ORM\Entity
 */
class Format
{
    /**
     * @var int
     *
     * @ORM\Column(name="format_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $formatId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="format", type="string", length=255, nullable=true)
     */
    private $format;

    public function getFormatId(): ?int
    {
        return $this->formatId;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }
}
