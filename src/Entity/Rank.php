<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rank.
 *
 * @ORM\Table(name="rank", indexes={@ORM\Index(name="fk_rank_subject_id_idx", columns={"subject_id"}), @ORM\Index(name="fk_rank_source_id_idx", columns={"source_id"}), @ORM\Index(name="fk_rank_title_id_idx", columns={"title_id"})})
 * @ORM\Entity
 */
class Rank
{
    /**
     * @var int
     *
     * @ORM\Column(name="rank_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rankId;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank = 0;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_override", type="text", length=65535, nullable=true)
     */
    private $descriptionOverride;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="dbbysub_active", type="boolean", nullable=true)
     */
    private $dbbysubActive = true;

    /**
     * @var \Source
     *
     * @ORM\ManyToOne(targetEntity="Source")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="source_id", referencedColumnName="source_id")
     * })
     */
    private $source;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="subject_id", referencedColumnName="subject_id")
     * })
     */
    private $subject;

    /**
     * @var \Title
     *
     * @ORM\ManyToOne(targetEntity="Title")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="title_id", referencedColumnName="title_id")
     * })
     */
    private $title;

    public function getRankId(): ?int
    {
        return $this->rankId;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getDescriptionOverride(): ?string
    {
        return $this->descriptionOverride;
    }

    public function setDescriptionOverride(?string $descriptionOverride): self
    {
        $this->descriptionOverride = $descriptionOverride;

        return $this;
    }

    public function getDbbysubActive(): ?bool
    {
        return $this->dbbysubActive;
    }

    public function setDbbysubActive(?bool $dbbysubActive): self
    {
        $this->dbbysubActive = $dbbysubActive;

        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

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

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(?Title $title): self
    {
        $this->title = $title;

        return $this;
    }
}
