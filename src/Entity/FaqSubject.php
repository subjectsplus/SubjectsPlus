<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaqSubject
 *
 * @ORM\Table(name="faq_subject", indexes={@ORM\Index(name="fk_fs_faq_id_idx", columns={"faq_id"}), @ORM\Index(name="fk_fs_subject_id_idx", columns={"subject_id"})})
 * @ORM\Entity
 */
class FaqSubject
{
    /**
     * @var int
     *
     * @ORM\Column(name="faq_subject_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $faqSubjectId;

    /**
     * @var \Faq
     *
     * @ORM\ManyToOne(targetEntity="Faq")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="faq_id", referencedColumnName="faq_id")
     * })
     */
    private $faq;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     * })
     */
    private $subject;

    public function getFaqSubjectId(): ?int
    {
        return $this->faqSubjectId;
    }

    public function getFaq(): ?Faq
    {
        return $this->faq;
    }

    public function setFaq(?Faq $faq): self
    {
        $this->faq = $faq;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
