<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Backend\Api\MediaUploader;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * 
 * @ApiResource(
 *     itemOperations={"get", "put", "delete"},
 *     order={"createdAt": "DESC", "updatedAt": "DESC"},
 *     normalizationContext={"groups": {"media"}},
 *     collectionOperations={
 *         "get", 
 *         "post" = {
 *              "controller" = MediaUploader::class,
 *              "deserialize" = false,
 *              "openapi_context" = {
 *                  "requestBody" = {
 *                      "description" = "File upload to an existing resource (Media)",
 *                      "required" = true,
 *                      "content" = {
 *                          "multipart/form-data" = {
 *                              "schema" = {
 *                                  "type" = "object",
 *                                  "properties" = {
 *                                      "title" = {
 *                                          "description" = "The title of the Media",
 *                                          "type" = "string",
 *                                          "example" = "Untitled",
 *                                      },
 *                                      "caption" = {
 *                                          "description" = "The caption of the Media",
 *                                          "type" = "string",
 *                                      },
 *                                      "altText" = {
 *                                          "description" = "The alternative text of the Media",
 *                                          "type" = "string",
 *                                      },
 *                                      "file" = {
 *                                          "type" = "string",
 *                                          "format" = "binary",
 *                                          "description" = "Upload a media file",
 *                                      },
 *                                  },
 *                              },
 *                          },
 *                      },
 *                  },
 *              }
 *          }
 *      }
 * )
 */
class Media implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="media_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"media"})
     */
    private $mediaId;

    /**
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     * @Groups({"media"})
     * 
     * @var string|null
     */
    private $fileName;

    /**
     * @ORM\Column(name="large_file_name", type="string", nullable=true)
     * @Groups({"media"})
     * 
     * @var string|null
     */
    private $largeFileName;

    /**
     * @ORM\Column(name="medium_file_name", type="string", nullable=true)
     * @Groups({"media"})
     * 
     * @var string|null
     */
    private $mediumFileName;

    /**
     * @ORM\Column(name="small_file_name", type="string", nullable=true)
     * @Groups({"media"})
     * 
     * @var string|null
     */
    private $smallFileName;

    /**
     * @ORM\Column(name="file_size", type="integer", nullable=true)
     * @Groups({"media"})
     * 
     * @var int|null
     */
    private $fileSize;

    /**
     * @ORM\Column(name="mime_type", type="string", nullable=true)
     * @Groups({"media"})
     * 
     * @var string|null
     */
    private $mimeType;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     * @Groups({"media"})
     * 
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime_immutable", nullable=true)
     * @Groups({"media"})
     * 
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime_immutable", nullable=true)
     * @Groups({"media"})
     * 
     * @var \DateTimeInterface|null
     */
    private $deletedAt;

    /**
     * @var Staff|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Staff")
     * @ORM\JoinColumn(name="staff_id", referencedColumnName="staff_id")
     * @Groups({"media"})
     */
    private $staff;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="title", type="string", nullable=true)
     * @Groups({"media"})
     */
    private $title = "Untitled";

    /**
     * @var string|null
     * 
     * @ORM\Column(name="caption", type="text", nullable=true)
     * @Groups({"media"})
     */
    private $caption;

    /**
     * @var string|null
     * 
     * @ORM\Column(name="alt_text", type="text", nullable=true)
     * @Groups({"media"})
     */
    private $altText;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"media"})
     */
    private $directory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $largeImageFileWidth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $largeImageFileHeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $mediumImageFileWidth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $mediumImageFileHeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $smallImageFileWidth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"media"})
     */
    private $smallImageFileHeight;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function setLargeFileName(?string $fileName): void
    {
        $this->largeFileName = $fileName;
    }

    public function getLargeFileName(): ?string
    {
        return $this->largeFileName;
    }

    public function setMediumFileName(?string $fileName): void
    {
        $this->mediumFileName = $fileName;
    }

    public function getMediumFileName(): ?string
    {
        return $this->mediumFileName;
    }

    public function setSmallFileName(?string $fileName): void
    {
        $this->smallFileName = $fileName;
    }

    public function getSmallFileName(): ?string
    {
        return $this->smallFileName;
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

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }
    
    public function getLargeImageFileWidth(): ?int
    {
        return $this->largeImageFileWidth;
    }

    public function setLargeImageFileWidth(?int $largeImageFileWidth): self
    {
        $this->largeImageFileWidth = $largeImageFileWidth;

        return $this;
    }

    public function getLargeImageFileHeight(): ?int
    {
        return $this->largeImageFileHeight;
    }

    public function setLargeImageFileHeight(?int $largeImageFileHeight): self
    {
        $this->largeImageFileHeight = $largeImageFileHeight;

        return $this;
    }

    public function getMediumImageFileWidth(): ?int
    {
        return $this->mediumImageFileWidth;
    }

    public function setMediumImageFileWidth(?int $mediumImageFileWidth): self
    {
        $this->mediumImageFileWidth = $mediumImageFileWidth;

        return $this;
    }

    public function getMediumImageFileHeight(): ?int
    {
        return $this->mediumImageFileHeight;
    }

    public function setMediumImageFileHeight(?int $mediumImageFileHeight): self
    {
        $this->mediumImageFileHeight = $mediumImageFileHeight;

        return $this;
    }

    public function getSmallImageFileWidth(): ?int
    {
        return $this->smallImageFileWidth;
    }

    public function setSmallImageFileWidth(?int $smallImageFileWidth): self
    {
        $this->smallImageFileWidth = $smallImageFileWidth;

        return $this;
    }

    public function getSmallImageFileHeight(): ?int
    {
        return $this->smallImageFileHeight;
    }

    public function setSmallImageFileHeight(?int $smallImageFileHeight): self
    {
        $this->smallImageFileHeight = $smallImageFileHeight;

        return $this;
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