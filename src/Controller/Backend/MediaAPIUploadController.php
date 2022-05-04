<?php

namespace App\Controller\Backend;

use App\Entity\Media;
use App\Entity\Staff;
use App\Service\MediaService;
use App\Service\ChangeLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Psr\Log\LoggerInterface;

class MediaAPIUploadController extends AbstractController
{
    public function __invoke(Request $request, ChangeLogService $cls, MediaService $uploader, LoggerInterface $logger): Media
    {
        /** @var UploadedFile $upload */
        $upload = $request->files->get('file');

        $title = $request->get('title');
        $caption = $request->get('caption');
        $altText = $request->get('altText');

        if (!$upload) {
            throw new BadRequestHttpException('"file" is required');
        } else if ($upload->getError() === UPLOAD_ERR_INI_SIZE) {
            throw new BadRequestHttpException('"file" exceeds max upload file size');
        }

        $media = new Media();
        $media->setTitle($title);
        $media->setCaption($caption);
        $media->setAltText($altText);

        /** @var Staff $staff */
        // TODO: Accept staff as a parameter
        $staff = $this->getUser();
        
        /** @var Media $resultingMedia */
        $resultingMedia = $uploader->handleUploadFile($upload, $media, $staff);

        // Create new log entry
        /** @var Staff $staff */
        $staff = $this->getUser();
        $cls->addLog($staff, 'media', $resultingMedia->getMediaId(), $resultingMedia->getTitle(), 'insert');
 
        return $resultingMedia;
    }
}