<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Location.
 *
 * @ORM\Table(name="location", indexes={@ORM\Index(name="fk_location_format_id_idx", columns={"format"}), @ORM\Index(name="fk_location_restrictions_id_idx", columns={"access_restrictions"})})
 * @ORM\Entity
 * 
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 */
class Location
{
    /**
     * @var int
     *
     * @ORM\Column(name="location_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $locationId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="call_number", type="string", length=255, nullable=true)
     */
    private $callNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="location", type="text", length=65535, nullable=true)
     */
    private $location;

    /**
     * @var string|null
     *
     * @ORM\Column(name="eres_display", type="string", length=1, nullable=true)
     */
    private $eresDisplay;

    /**
     * @var string|null
     *
     * @ORM\Column(name="display_note", type="text", length=65535, nullable=true)
     */
    private $displayNote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="helpguide", type="string", length=255, nullable=true)
     */
    private $helpguide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="citation_guide", type="string", length=255, nullable=true)
     */
    private $citationGuide;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ctags", type="string", length=255, nullable=true)
     */
    private $ctags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="record_status", type="string", length=255, nullable=true)
     */
    private $recordStatus;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="trial_start", type="date", nullable=true)
     */
    private $trialStart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="trial_end", type="date", nullable=true)
     */
    private $trialEnd;

    /**
     * @var \Format
     *
     * @ORM\ManyToOne(targetEntity="Format")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="format", referencedColumnName="format_id")
     * })
     */
    private $format;

    /**
     * @var \Restrictions
     *
     * @ORM\ManyToOne(targetEntity="Restrictions")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="access_restrictions", referencedColumnName="restrictions_id")
     * })
     */
    private $accessRestrictions;

    /**
     * @var \Title|null
     *
     * @ORM\ManyToOne(targetEntity="Title", inversedBy="location")
     * @ORM\JoinColumn(name="title_id", referencedColumnName="title_id")
     */
    private $title;

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function getCallNumber(): ?string
    {
        return $this->callNumber;
    }

    public function setCallNumber(?string $callNumber): self
    {
        $this->callNumber = $callNumber;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getEresDisplay(): ?string
    {
        return $this->eresDisplay;
    }

    public function setEresDisplay(?string $eresDisplay): self
    {
        $this->eresDisplay = $eresDisplay;

        return $this;
    }

    public function getDisplayNote(): ?string
    {
        return $this->displayNote;
    }

    public function setDisplayNote(?string $displayNote): self
    {
        $this->displayNote = $displayNote;

        return $this;
    }

    public function getHelpguide(): ?string
    {
        return $this->helpguide;
    }

    public function setHelpguide(?string $helpguide): self
    {
        $this->helpguide = $helpguide;

        return $this;
    }

    public function getCitationGuide(): ?string
    {
        return $this->citationGuide;
    }

    public function setCitationGuide(?string $citationGuide): self
    {
        $this->citationGuide = $citationGuide;

        return $this;
    }

    public function getCtags(): ?string
    {
        return $this->ctags;
    }

    public function setCtags(?string $ctags): self
    {
        $this->ctags = $ctags;

        return $this;
    }

    public function getRecordStatus(): ?string
    {
        return $this->recordStatus;
    }

    public function setRecordStatus(?string $recordStatus): self
    {
        $this->recordStatus = $recordStatus;

        return $this;
    }

    public function getTrialStart(): ?\DateTimeInterface
    {
        return $this->trialStart;
    }

    public function setTrialStart(?\DateTimeInterface $trialStart): self
    {
        $this->trialStart = $trialStart;

        return $this;
    }

    public function getTrialEnd(): ?\DateTimeInterface
    {
        return $this->trialEnd;
    }

    public function setTrialEnd(?\DateTimeInterface $trialEnd): self
    {
        $this->trialEnd = $trialEnd;

        return $this;
    }

    public function getFormat(): ?Format
    {
        return $this->format;
    }

    public function setFormat(?Format $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getAccessRestrictions(): ?Restrictions
    {
        return $this->accessRestrictions;
    }

    public function setAccessRestrictions(?Restrictions $accessRestrictions): self
    {
        $this->accessRestrictions = $accessRestrictions;

        return $this;
    }

    /**
     * @return Title|null
     */
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
