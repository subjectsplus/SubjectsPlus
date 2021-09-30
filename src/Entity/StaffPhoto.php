<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="staff_photo")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class StaffPhoto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="staffphoto_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $staffPhotoId;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="staff_photo", fileNameProperty="imageName", size="imageSize")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(name="image_name", type="string", nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(name="image_size", type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

        /**
     * @var StaffPhoto|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Staff", inversedBy="staff_photo")
     * @ORM\JoinColumn(name="staff_id", referencedColumnName="staff_id")
     */
    private $staff;

    public function __construct()
    {
        $this->createdAt =new \DateTimeImmutable();
    }

    public function getStaffPhotoId(): ?int
    {
        return $this->staffPhotoId;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
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
}