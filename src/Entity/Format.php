<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="format_id", type="integer", nullable=false)
     */
    private $formatId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="format", type="string", length=255, nullable=true)
     */
    private $format;

    /**
     * @ORM\OneToMany(targetEntity=Record::class, mappedBy="format")
     */
    private $records;

    public function __construct()
    {
        $this->records = new ArrayCollection();
    }



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
    public function __toString(): string {
        return $this->format;
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
            $record->setFormat($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): self
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getFormat() === $this) {
                $record->setFormat(null);
            }
        }

        return $this;
    }


}
