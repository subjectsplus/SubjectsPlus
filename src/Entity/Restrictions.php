<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Record::class, mappedBy="restriction")
     */
    private $records;

    public function __construct()
    {
        $this->records = new ArrayCollection();
    }



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

    /**
     * @return Collection<int, Record>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Record $record): self
    {
        if (!$this->records->contains($record)) {
            $this->records[] = $record;
            $record->setRestriction($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getRestriction() === $this) {
                $record->setRestriction(null);
            }
        }

        return $this;
    }

}
