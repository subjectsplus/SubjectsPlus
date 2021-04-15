<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Video.
 *
 * @ORM\Table(name="video", indexes={@ORM\Index(name="INDEXSEARCH", columns={"title", "description"})})
 * @ORM\Entity
 */
class Video
{
    /**
     * @var int
     *
     * @ORM\Column(name="video_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $videoId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=false)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="foreign_id", type="string", length=255, nullable=false)
     */
    private $foreignId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="duration", type="string", length=50, nullable=true)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="display", type="integer", nullable=false)
     */
    private $display = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="vtags", type="string", length=255, nullable=true)
     */
    private $vtags;

    public function getVideoId(): ?int
    {
        return $this->videoId;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getForeignId(): ?string
    {
        return $this->foreignId;
    }

    public function setForeignId(string $foreignId): self
    {
        $this->foreignId = $foreignId;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
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

    public function getDisplay(): ?int
    {
        return $this->display;
    }

    public function setDisplay(int $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getVtags(): ?string
    {
        return $this->vtags;
    }

    public function setVtags(?string $vtags): self
    {
        $this->vtags = $vtags;

        return $this;
    }
}
