<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;

class MediaService {

    private $entityManager;
    private $fileNamer;
    private $projectDir;
    private $uploadDestination;
    

    public function __construct(EntityManagerInterface $entityManager, FileNamerService $fileNamer, string $projectDir, string $uploadDestination)
    {
        $this->entityManager = $entityManager;
        $this->fileNamer = $fileNamer;
        $this->projectDir = $projectDir;
        $this->uploadDestination = $uploadDestination;
    }

    public function uploadFile(UploadedFile $file) {
        // todo: check if file has already been uploaded before
        // todo: create a file namer service
        // todo: file validation
        // todo: exception handling
        // todo: doctrine transactions
        
        // move file to upload destination
        $name = $this->fileNamer->fileName($file);
        $subDirName = $this->fileNamer->directoryName($file);
        $publicDestination = join(DIRECTORY_SEPARATOR, [
            $this->uploadDestination, 
            $subDirName
        ]);
        $absDestination = join(DIRECTORY_SEPARATOR, [
            $this->projectDir, 
            'public/',
            $publicDestination,
        ]);
        $file = $file->move($absDestination, $name);

        return ['file' => $file, 'fileName' => $name, 'url' => $publicDestination . $name];
    }

    public function getRelativeUrlFromMedia(Media $media) { 
        $mimeType = $media->getMimeType();

        if ($mimeType === null) return null;

        $subDirName = $this->fileNamer->getSubDirectoryFromMimeType($mimeType);
        $publicDestination = join(DIRECTORY_SEPARATOR, [
            $this->uploadDestination, 
            $subDirName
        ]);
        $fileName = $media->getFileName();
        $relativeUrl = sprintf("%s/%s", $publicDestination, $fileName);

        return $relativeUrl;

    }

    public function createMedia(?string $fileName = null, ?File $file = null, ?Staff $uploader = null)
    {
        $media = new Media();
        $media->setFileName($fileName);
        $media->setFile($file);

        if ($file !== null) {
            $media->setFileSize($file->getSize());
            $media->setMimeType($file->getMimeType());
        } else {
            $media->setFileSize(null);
            $media->setMimeType(null);
        }

        $media->setStaff($uploader);

        return $media;
    }

    public function createMediaAttachment(?Media $media, ?string $attachmentType,  ?int $attachmentId)
    {
        $mediaAttachment = new MediaAttachment();
        $mediaAttachment->setMedia($media);
        $mediaAttachment->setAttachmentType($attachmentType);
        $mediaAttachment->setAttachmentId($attachmentId);
        return $mediaAttachment;
    }

    public static function determineValidationGroups(FormInterface $form)
    {
        $fileData = $form->get('file')->getData();

        if ($fileData instanceof File) {
            $mimeType = $fileData->getMimeType();
            if ($mimeType !== null) {
                if (strpos($mimeType, "image/") !== false) {
                    return ['image'];
                } else {
                    return ['generic'];
                }
            }
        }
        return ['Default'];
    }

    public function storeImageAssetsFromHTML(string $html, string $attachmentType, int $attachmentId) {
        // Load the html from ckeditor form field
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all image tags and isolate the source attribute
        /** @var \DomNodeList $imageSources */
        $imageSources = $xpath->query('//img/@src');
                
        $mediaRepo = $this->entityManager->getRepository(Media::class);
        if ($imageSources->count() > 0) {
            foreach ($imageSources as $imageSource) {
                $filePath = $imageSource->nodeValue;
                $fileName = basename($filePath);
                
                /** @var Media $media */
                $media = $mediaRepo->findOneBy(['fileName' => $fileName]);
                
                if ($media) {
                    $mediaAttachment = new MediaAttachment();
                    $mediaAttachment->setMedia($media);
                    $mediaAttachment->setAttachmentType($attachmentType);
                    $mediaAttachment->setAttachmentId($attachmentId);
                    $this->entityManager->persist($mediaAttachment);
                } else {
                    // check if file exists
                    if (file_exists($filePath)) {
                        
                    } else {
                        // report as a missing file
                    }
                }
            }
        }
    }
}