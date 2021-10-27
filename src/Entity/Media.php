<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="media_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mediaId;

    /**
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     *
     * @var string|null
     */
    private $fileName;

    /**
     * @ORM\Column(name="file_size", type="integer", nullable=true)
     *
     * @var int|null
     */
    private $fileSize;

    /**
     * @ORM\Column(name="mime_type", type="string", nullable=true)
     *
     * @var string|null
     */
    private $mimeType;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     *
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $deletedAt;

    /**
     * @var Staff|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Staff")
     * @ORM\JoinColumn(name="staff_id", referencedColumnName="staff_id")
     */
    private $staff;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title = "Untitled";

    /**
     * @var string|null
     * 
     * @ORM\Column(name="caption", type="text", nullable=true)
     */
    private $caption;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="alt_text", type="text", nullable=true)
     */
    private $altText;

    public function getMediaId(): ?int
    {
        return $this->mediaId;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            if ($this->createdAt !== null) {
                $this->updatedAt = new \DateTimeImmutable();
                $this->deletedAt = null;
            } else {
                $this->createdAt = new \DateTimeImmutable();
            }
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }
    
    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
    
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setStaff(?Staff $staff): self 
    {
        $this->staff = $staff;

        return $this;
    }

    public function getStaff(): ?Staff
    {
        return $this->staff;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->mediaId,
            $this->file,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
        $this->mediaId,
        $this->file,
        ) = unserialize($serialized, array('allowed_classes' => false));
    }
}