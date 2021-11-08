<?php

namespace App\EventListener;

use Vich\UploaderBundle\Event\Event;
use App\Entity\Media;

class UploadPostRemoveListener
{
    public function onVichUploaderPostRemove(Event $event)
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        if ($object instanceof Media) {
            $object->setDeletedAt(new \DateTimeImmutable());
        }
    }

}