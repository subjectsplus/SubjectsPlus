<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Talkback
 *
 * @ORM\Table(name="talkback", indexes={@ORM\Index(name="INDEXSEARCHtalkback", columns={"question", "answer"}), @ORM\Index(name="fk_talkback_staff_id_idx", columns={"a_from"})})
 * @ORM\Entity
 */
class Talkback
{
    /**
     * @var int
     *
     * @ORM\Column(name="talkback_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $talkbackId;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text", length=65535, nullable=false)
     */
    private $question;

    /**
     * @var string|null
     *
     * @ORM\Column(name="q_from", type="string", length=100, nullable=true)
     */
    private $qFrom = '';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_submitted", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $dateSubmitted = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", length=65535, nullable=false)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="display", type="string", length=11, nullable=false, options={"default"="No"})
     */
    private $display = 'No';

    /**
     * @var string
     *
     * @ORM\Column(name="last_revised_by", type="string", length=100, nullable=false)
     */
    private $lastRevisedBy = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="tbtags", type="string", length=255, nullable=true, options={"default"="main"})
     */
    private $tbtags = 'main';

    /**
     * @var string|null
     *
     * @ORM\Column(name="cattags", type="string", length=255, nullable=true)
     */
    private $cattags;

    /**
     * @var \Staff
     *
     * @ORM\ManyToOne(targetEntity="Staff")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="a_from", referencedColumnName="staff_id")
     * })
     */
    private $aFrom;

    public function getTalkbackId(): ?int
    {
        return $this->talkbackId;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getQFrom(): ?string
    {
        return $this->qFrom;
    }

    public function setQFrom(?string $qFrom): self
    {
        $this->qFrom = $qFrom;

        return $this;
    }

    public function getDateSubmitted(): ?\DateTimeInterface
    {
        return $this->dateSubmitted;
    }

    public function setDateSubmitted(\DateTimeInterface $dateSubmitted): self
    {
        $this->dateSubmitted = $dateSubmitted;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getLastRevisedBy(): ?string
    {
        return $this->lastRevisedBy;
    }

    public function setLastRevisedBy(string $lastRevisedBy): self
    {
        $this->lastRevisedBy = $lastRevisedBy;

        return $this;
    }

    public function getTbtags(): ?string
    {
        return $this->tbtags;
    }

    public function setTbtags(?string $tbtags): self
    {
        $this->tbtags = $tbtags;

        return $this;
    }

    public function getCattags(): ?string
    {
        return $this->cattags;
    }

    public function setCattags(?string $cattags): self
    {
        $this->cattags = $cattags;

        return $this;
    }

    public function getAFrom(): ?Staff
    {
        return $this->aFrom;
    }

    public function setAFrom(?Staff $aFrom): self
    {
        $this->aFrom = $aFrom;

        return $this;
    }
}
