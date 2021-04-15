<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubjectSubject.
 *
 * @ORM\Table(name="subject_subject", indexes={@ORM\Index(name="fk_subject_parent_idx", columns={"subject_parent"}), @ORM\Index(name="fk_subject_child_idx", columns={"subject_child"})})
 * @ORM\Entity
 */
class SubjectSubject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_subject_subject", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSubjectSubject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subject_child", referencedColumnName="subject_id")
     * })
     */
    private $subjectChild;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subject_parent", referencedColumnName="subject_id")
     * })
     */
    private $subjectParent;

    public function getIdSubjectSubject(): ?int
    {
        return $this->idSubjectSubject;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSubjectChild(): ?Subject
    {
        return $this->subjectChild;
    }

    public function setSubjectChild(?Subject $subjectChild): self
    {
        $this->subjectChild = $subjectChild;

        return $this;
    }

    public function getSubjectParent(): ?Subject
    {
        return $this->subjectParent;
    }

    public function setSubjectParent(?Subject $subjectParent): self
    {
        $this->subjectParent = $subjectParent;

        return $this;
    }
}
