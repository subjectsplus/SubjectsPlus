<?php

namespace App\Service;


use App\Repository\StaffRepository;
use App\Service\MediaService;
use App\Entity\MediaAttachment;
use App\Entity\Media;
use App\Entity\Staff;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class StaffService
{
    /**
     * @var MediaService $uploader
     */
    private $uploader;

    /**
     * Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(MediaService $uploader, EntityManagerInterface $entityManager) {
        $this->uploader = $uploader;
        $this->entityManager = $entityManager;
    }

    public function processStaffPhoto(UploadedFile $file, Staff $staff)
    {
        $this->entityManager->wrapInTransaction(function() use($file, $staff) {
            // Create needed entities
            $media = new Media();

            // Only create a new media attachment if one does not already exist
            $mediaAttachment = $this->entityManager->getRepository(MediaAttachment::class)->findOneBy([
                'attachmentType' => 'staff_photo',
                'attachmentId' => $staff->getStaffId()
            ]);

            if ($mediaAttachment === null) {
                $mediaAttachment = new MediaAttachment();
            }

            // Persist the entities
            $this->entityManager->persist($media);
            $this->entityManager->persist($mediaAttachment);
            $this->entityManager->persist($staff);

            // Handle file upload
            $this->uploader->handleUploadFile($file, $media, $staff);

            // Set media attachment details
            $mediaAttachment->setMedia($media);
            $mediaAttachment->setAttachmentType('staff_photo');
            $mediaAttachment->setAttachmentId($staff->getStaffId());

            // Set media attachment as photo for Staff entity
            $staff->setStaffPhoto($mediaAttachment);
        });
    }
}