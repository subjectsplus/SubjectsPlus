<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Discipline.
 *
 * @ORM\Table(name="discipline", uniqueConstraints={@ORM\UniqueConstraint(name="discipline", columns={"discipline"})})
 * @ORM\Entity
 */
class Discipline
{
    /**
     * @var int
     *
     * @ORM\Column(name="discipline_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $disciplineId;

    /**
     * @var string
     *
     * @ORM\Column(name="discipline", type="string", length=100, nullable=false)
     */
    private $discipline;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Subject", mappedBy="discipline")
     */
    private $subject;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subject = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getDisciplineId(): ?int
    {
        return $this->disciplineId;
    }

    public function getDiscipline(): ?string
    {
        return $this->discipline;
    }

    public function setDiscipline(string $discipline): self
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subject->contains($subject)) {
            $this->subject[] = $subject;
            $subject->addDiscipline($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subject->removeElement($subject)) {
            $subject->removeDiscipline($this);
        }

        return $this;
    }
}
