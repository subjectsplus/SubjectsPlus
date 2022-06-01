<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 */
class Record
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alternate_title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $internal_notes;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $pre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $call_number;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $eres_display;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $display_note;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $trial_start;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $trial_end;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $record_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAlternateTitle(): ?string
    {
        return $this->alternate_title;
    }

    public function setAlternateTitle(?string $alternate_title): self
    {
        $this->alternate_title = $alternate_title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInternalNotes(): ?string
    {
        return $this->internal_notes;
    }

    public function setInternalNotes(?string $internal_notes): self
    {
        $this->internal_notes = $internal_notes;

        return $this;
    }

    public function getPre(): ?string
    {
        return $this->pre;
    }

    public function setPre(?string $pre): self
    {
        $this->pre = $pre;

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

    public function getCallNumber(): ?string
    {
        return $this->call_number;
    }

    public function setCallNumber(?string $call_number): self
    {
        $this->call_number = $call_number;

        return $this;
    }

    public function getEresDisplay(): ?string
    {
        return $this->eres_display;
    }

    public function setEresDisplay(string $eres_display): self
    {
        $this->eres_display = $eres_display;

        return $this;
    }

    public function getDisplayNote(): ?string
    {
        return $this->display_note;
    }

    public function setDisplayNote(?string $display_note): self
    {
        $this->display_note = $display_note;

        return $this;
    }

    public function getTrialStart(): ?\DateTimeInterface
    {
        return $this->trial_start;
    }

    public function setTrialStart(?\DateTimeInterface $trial_start): self
    {
        $this->trial_start = $trial_start;

        return $this;
    }

    public function getTrialEnd(): ?\DateTimeInterface
    {
        return $this->trial_end;
    }

    public function setTrialEnd(?\DateTimeInterface $trial_end): self
    {
        $this->trial_end = $trial_end;

        return $this;
    }

    public function getRecordStatus(): ?string
    {
        return $this->record_status;
    }

    public function setRecordStatus(string $record_status): self
    {
        $this->record_status = $record_status;

        return $this;
    }
}
