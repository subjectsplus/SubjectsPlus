<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity
 */
class Source
{
    /**
     * @var int
     *
     * @ORM\Column(name="source_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sourceId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rs", type="integer", nullable=true)
     */
    private $rs;

    public function getSourceId(): ?string
    {
        return $this->sourceId;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getRs(): ?int
    {
        return $this->rs;
    }

    public function setRs(?int $rs): self
    {
        $this->rs = $rs;

        return $this;
    }
}
