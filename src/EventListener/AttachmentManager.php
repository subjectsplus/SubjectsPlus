<?php

namespace App\EventListener;

use App\Entity\Pluslet;
use App\Entity\Faq;
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