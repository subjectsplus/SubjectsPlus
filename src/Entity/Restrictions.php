<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Restrictions.
 *
 * @ORM\Table(name="restrictions")
 * @ORM\Entity
 */
class Restrictions
{
    /**
     * @var int
     *
     * @ORM\Column(name="restrictions_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $restrictionsId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="restrictions", type="text", length=65535, nullable=true)
     */
    private $restrictions;

    public function getRestrictionsId(): ?int
    {
        return $this->restrictionsId;
    }

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictionsId(?int $restrictionsId): self
    {
        $this->restrictionsId = $restrictionsId;

        return $this;
    }

    public function setRestrictions(?string $restrictions): self
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function __toString(): string {
        return $this->restrictions;
    }
}
