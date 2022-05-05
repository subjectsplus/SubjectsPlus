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
use Symfony\Component\Validator\Exception\ValidatorException;

class MediaAPIUploadController extends AbstractController
{
    public function __invoke(Request $request, ChangeLogService $cls, MediaService $uploader): Media
    {
        // Gather the request fields
        /** @var UploadedFile $upload */
        $upload = $request->files->get('file');
        $title = $request->get('title');
        $caption = $request->get('caption');
        $altText = $request->get('altText');

        // Check for errors with uploaded file
        if (!$upload) {
            throw new BadRequestHttpException('"file" is required');
        } else if ($upload->getError() === UPLOAD_ERR_INI_SIZE) {
            throw new BadRequestHttpException('"file" exceeds max upload file size');
        } else if ($upload->getError() > 1) {
            // Internal error caused by the server, nothing that the end user has control over
            // LogController page will show the full error details
            // For upload error messages/codes, refer to: https://www.php.net/manual/en/features.file-upload.errors.php
            throw new \Exception('File upload has failed');
        }

        // Construct the Media object
        $media = new Media();
        $media->setTitle($title);
        $media->setCaption($caption);
        $media->setAltText($altText);

        /** @var Staff $staff */
        // TODO: Accept staff as a parameter
        $staff = $this->getUser();
        
        // Upload file to media
        try {
            $uploader->handleUploadFile($upload, $media, $staff);
        } catch(ValidatorException $e) {
            // Validation failed
            throw new BadRequestHttpException($e->getMessage());
        }

        // Create new log entry
        /** @var Staff $staff */
        $staff = $this->getUser();
        $cls->addLog($staff, 'media', $media->getMediaId(), $media->getTitle(), 'insert');
 
        return $media;
    }
}