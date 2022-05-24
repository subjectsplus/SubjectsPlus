<?php

namespace App\EventListener;

use App\Entity\Pluslet;
use App\Entity\Faq;
use App\Entity\MediaAttachment;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\MediaService;

class AttachmentManager
{
    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Pluslet) {
            // Manage attachments changes for Pluslet Body
            $this->mediaService->removeAttachmentFromHTML($entity->getBody(), 'pluslet', $entity->getPlusletId());
            $this->mediaService->createAttachmentFromHTML($entity->getBody(), 'pluslet', $entity->getPlusletId());
        } else if ($entity instanceof Faq) {
            // Manage attachment changes for Faq Answer
            $this->mediaService->removeAttachmentFromHTML($entity->getAnswer(), 'faq', $entity->getFaqId());
            $this->mediaService->createAttachmentFromHTML($entity->getAnswer(), 'faq', $entity->getFaqId());
        }

        return;
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof Pluslet) {
            // Removes attachments for the Pluslet
            $mediaAttachments = $entityManager
            ->getRepository(MediaAttachment::class)
            ->findBy(['attachmentType' => 'pluslet', 'attachmentId' => $entity->getPlusletId()]);
            if (!empty($mediaAttachments)) {
                foreach($mediaAttachments as $attachment) {
                    $entityManager->remove($attachment);
                }

                $entityManager->flush();
            }
        } else if ($entity instanceof Faq) {
            // Removes attachments for the Faq
            $mediaAttachments = $entityManager
            ->getRepository(MediaAttachment::class)
            ->findBy(['attachmentType' => 'faq', 'attachmentId' => $entity->getFaqId()]);

            if (!empty($mediaAttachments)) {
                foreach($mediaAttachments as $attachment) {
                    $entityManager->remove($attachment);
                }

                $entityManager->flush();
            }
        }

        return;
    }

    public function faqPostUpdate(Faq $faq)
    {
        // Manage attachment changes for Faq Answer
        $this->mediaService->removeAttachmentFromHTML($faq->getAnswer(), 'faq', $faq->getFaqId());
        $this->mediaService->createAttachmentFromHTML($faq->getAnswer(), 'faq', $faq->getFaqId());
    }

    public function plusletPostUpdate(Pluslet $pluslet) {
        // Manage attachments changes for Pluslet Body
        $this->mediaService->removeAttachmentFromHTML($pluslet->getBody(), 'pluslet', $pluslet->getPlusletId());
        $this->mediaService->createAttachmentFromHTML($pluslet->getBody(), 'pluslet', $pluslet->getPlusletId());
    }
}