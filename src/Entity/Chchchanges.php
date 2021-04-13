<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chchchanges
 *
 * @ORM\Table(name="chchchanges")
 * @ORM\Entity
 */
class Chchchanges
{
    /**
     * @var int
     *
     * @ORM\Column(name="chchchanges_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $chchchangesId;

    /**
     * @var int
     *
     * @ORM\Column(name="staff_id", type="integer", nullable=false)
     */
    private $staffId;

    /**
     * @var string
     *
     * @ORM\Column(name="ourtable", type="string", length=50, nullable=false)
     */
    private $ourtable;

    /**
     * @var int
     *
     * @ORM\Column(name="record_id", type="integer", nullable=false)
     */
    private $recordId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="record_title", type="string", length=255, nullable=true)
     */
    private $recordTitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateAdded = 'CURRENT_TIMESTAMP';

    public function getChchchangesId(): ?string
    {
        return $this->chchchangesId;
    }

    public function getStaffId(): ?int
    {
        return $this->staffId;
    }

    public function setStaffId(int $staffId): self
    {
        $this->staffId = $staffId;

        return $this;
    }

    public function getOurtable(): ?string
    {
        return $this->ourtable;
    }

    public function setOurtable(string $ourtable): self
    {
        $this->ourtable = $ourtable;

        return $this;
    }

    public function getRecordId(): ?int
    {
        return $this->recordId;
    }

    public function setRecordId(int $recordId): self
    {
        $this->recordId = $recordId;

        return $this;
    }

    public function getRecordTitle(): ?string
    {
        return $this->recordTitle;
    }

    public function setRecordTitle(?string $recordTitle): self
    {
        $this->recordTitle = $recordTitle;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->dateAdded;
    }

    public function setDateAdded(\DateTimeInterface $dateAdded): self
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }
}
