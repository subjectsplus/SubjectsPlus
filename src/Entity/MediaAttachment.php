<?php

namespace App\Entity;

use App\Repository\MediaAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaAttachmentRepository::class)
 */
class MediaAttachment
{
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="media_attachment_id", type="integer", nullable=false)
     */
    private $mediaAttachmentId;

    /**
     * @var Media|null
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Media", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="media_id")
     */
    private $media;

    /**
     * @var string|null
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachmentType;

    /**
     * @var int|null
     * 
     * @ORM\Column(name="attachment_id", type="integer", nullable=true)
     */
    private $attachmentId;

    public function getMediaAttachmentId(): ?int
    {
        return $this->mediaAttachmentId;
    }

    public function setMediaAttachmentId(int $mediaAttachmentId): self
    {
        $this->mediaAttachmentId = $mediaAttachmentId;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getAttachmentType(): ?string
    {
        return $this->attachmentType;
    }

    public function setAttachmentType(?string $attachmentType): self
    {
        $this->attachmentType = $attachmentType;

        return $this;
    }

    public function getAttachmentId(): ?int
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(?int $attachmentId): self
    {
        $this->attachmentId = $attachmentId;

        return $this;
    }
}
