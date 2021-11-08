<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Media;
use App\Entity\MediaAttachment;
use App\Entity\Staff;
use Masterminds\HTML5;
use Psr\Log\LoggerInterface;

class MediaService {

    private $entityManager;
    private $fileNamer;
    private $projectDir;
    private $uploadDestination;
    private $smallImageDimensions;
    private $mediumImageDimensions;
    private $largeImageDimensions;
    private $logger;

    public const SMALL_IMAGE = 0;
    public const MEDIUM_IMAGE = 1;
    public const LARGE_IMAGE = 2;

    public function __construct(EntityManagerInterface $entityManager, FileNamerService $fileNamer, 
        LoggerInterface $logger, string $projectDir, string $uploadDestination, string $smallImageDimensions,
        string $mediumImageDimensions, string $largeImageDimensions)
    {
        $this->entityManager = $entityManager;
        $this->fileNamer = $fileNamer;
        $this->projectDir = rtrim($projectDir, "/\\");
        $this->uploadDestination = rtrim($uploadDestination, "/\\");
        $this->logger = $logger;

        // Set Image Dimensions
        list($width, $height) = explode('x', $smallImageDimensions, 2);
        $this->smallImageDimensions = [(float)$width, (float)$height];

        list($width, $height) = explode('x', $mediumImageDimensions, 2);
        $this->mediumImageDimensions = [(float)$width, (float)$height];

        list($width, $height) = explode('x', $largeImageDimensions, 2);
        $this->largeImageDimensions = [(float)$width, (float)$height];;
    }

    /**
     * Uploads a file to the file server.
     * 
     * Given a Symfony UploadedFile instance, the file undergoes an upload sequence consisting
     * of renaming the file to be a unique identifier, establishing the upload destination based on mime type,
     * and moving the file from the temporary location to the upload destination on the file server.
     * 
     * @param UploadedFile $file
     * @return array array containing 'file', 'fileName', 'path', and 'url'
     * 
     * @throws \Exception Upon an error, rollback will occur where file is deleted to preserve
     * the integrity of the file server.
     */
    public function uploadFile(UploadedFile $file) {
        $path = null;
        try {
            // move file to upload destination
            $name = $this->fileNamer->fileName($file);
            $subDirName = $this->fileNamer->directoryName($file);
            $publicDestination = join(DIRECTORY_SEPARATOR, [
                $this->uploadDestination,
                $subDirName
            ]);
            $absDestination = join(DIRECTORY_SEPARATOR, [
                $this->projectDir, 
                'public',
                trim($publicDestination, "/\\"),
            ]);

            // do not upload until file name is unique
            /** @var \App\Repository\MediaRepository $mediaRepo */
            $mediaRepo = $this->entityManager->getRepository(Media::class);

            while ($mediaRepo->findOneBy(['fileName' => $name]) !== null) {
                $name = $this->fileNamer->fileName($file);
            }

            // full absolute path of new file upload
            $path = join(DIRECTORY_SEPARATOR, [
                $absDestination,
                $name
            ]);

            // move file to absolute upload destination from tmp directory
            $file = $file->move($absDestination, $name);

            return ['file' => $file, 'fileName' => $name, 'path' => $path, 'url' => $publicDestination . $name];
        } catch (\Exception $e) {
            // rollback the file upload; delete file 
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
            throw $e;
        }
    }

    private function generateSizedImage(File $file, int $sizeType) {
        if ($sizeType !== self::SMALL_IMAGE && $sizeType !== self::MEDIUM_IMAGE
                && $sizeType !== self::LARGE_IMAGE) {
            throw new \Exception("Incorrect argument supplied for integer \$sizeType.");
        }

        $directory = pathinfo($file->getRealPath(), PATHINFO_DIRNAME);
        $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $fileExtension = $file->getExtension();
        
        if ($sizeType == self::SMALL_IMAGE) {
            $newWidth = $this->smallImageDimensions[0];
            $newHeight = $this->smallImageDimensions[1];
            $type = 'small';
        } else if ($sizeType == self::MEDIUM_IMAGE) {
            $newWidth = $this->mediumImageDimensions[0];
            $newHeight = $this->mediumImageDimensions[1];
            $type = 'medium';
        } else {
            $newWidth = $this->largeImageDimensions[0];
            $newHeight = $this->largeImageDimensions[1];
            $type = 'large';
        }

        $newFileName = sprintf('%s_%s.%s', $fileName, $type, $fileExtension);
        $path = sprintf('%s/%s', $directory, $newFileName);

        if (copy($file->getRealPath(), $path) === true) {
            // Generate image
            $image = new \Imagick($path);
            //$res = $image->resizeImage($newWidth, $newHeight, \Imagick::FILTER_LANCZOS, 1, true);
            $res = $image->thumbnailImage($newWidth, $newHeight, true);
            
            if ($res === true) {
                $image->writeImage($path);
                $image->destroy();
                return $path;
            } else {
                throw new \Exception(sprintf("Failed to resize image to $type dimensions '%s'", $file->getRealPath()));
            }
        } else {
            throw new \Exception(sprintf("Failed to copy image in path '%s' to '%s'.",
                $file->getRealPath(), $path));
        }
    }

    public function generateSizedImages(File $file) {
        try {
            $mimeType = $file->getMimeType();
            if ($mimeType === null || strpos($mimeType, 'image/') === false) {
                return;
            }

            $generatedImages = [];

            // Load the image file in Imagick
            $path = $file->getRealPath();
            $image = new \Imagick($path);

            // Get dimensions of image file
            $width = $image->getImageWidth();
            $height = $image->getImageHeight();

            $image->destroy();
            
            if ($width > $this->smallImageDimensions[0] || $height > $this->smallImageDimensions[1]) {
                $generatedImages['small'] = $this->generateSizedImage($file, self::SMALL_IMAGE);
            }

            if ($width > $this->mediumImageDimensions[0] || $height > $this->mediumImageDimensions[1]) {
                $generatedImages['medium'] = $this->generateSizedImage($file, self::MEDIUM_IMAGE);
            }

            if ($width > $this->largeImageDimensions[0] || $height > $this->largeImageDimensions[1]) {
                $generateImages['large'] = $this->generateSizedImage($file, self::LARGE_IMAGE);
            }
            
            return $generatedImages;
        } catch (\Exception $e) {
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
            throw $e;
        }
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

    /**
     * Determines the validation group based on the mime type of the file uploaded.
     *
     * Uploaded images will return the image validation group while any other files will return the
     * generic validation group.
     *  
     * @param FormInterface $form
     * @return void
     */
    public static function determineValidationGroups(FormInterface $form)
    {
        $fileData = $form->get('file')->getData();

        if ($fileData instanceof File) {
            try {
                $mimeType = $fileData->getMimeType();
                if ($mimeType !== null) {
                    if (strpos($mimeType, "image/") !== false) {
                        return ['image'];
                    } else {
                        return ['generic'];
                    }
                }
            } catch (\Exception $e) {
                // This will most likely be an file upload size error
                // triggered by the ini requirements
                // Return and let symfony forms generate the error to client
                return;
            }
        }
        return ['Default'];
    }

    /**
     * Creates MediaAttachment entities from the inputted html source, attachment type, and attachment id.
     * 
     * The html source is parsed for media sources (links and images) and creates a MediaAttachment entity
     * to link the media source to the attachment.
     *  
     * @param string $html html source
     * @param string $attachmentType Type of attachment. Ex: 'faq', 'record'
     * @param integer $attachmentId Id of attachment
     * @return void
     */
    public function createAttachmentFromHTML(string $html, string $attachmentType, int $attachmentId) {
        // Load the html for parsing
        $html5 = new HTML5([
            'disable_html_ns' => true,
        ]);
        $doc = $html5->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all image/link tags and isolate the source attribute
        /** @var \DomNodeList $sources */
        $sources = [];
        $imageSources = $xpath->query('//img/@src');
        $linkSources = $xpath->query('//a/@href');
        
        // Copy into sources array for parsing
        foreach ($imageSources as $imageSource) {
            $sources[] = $imageSource;
        }

        foreach ($linkSources as $linkSource) {
            $sources[] = $linkSource;
        }

        if (count($sources) > 0) {
            foreach ($sources as $source) {
                $filePath = $source->nodeValue;
                if ($filePath[0] === DIRECTORY_SEPARATOR) {
                    // is an absolute file path
                    $filePath = join(DIRECTORY_SEPARATOR, [
                        $this->projectDir, 
                        'public',
                        trim($filePath, "/\\"),
                    ]);
                }

                if (file_exists($filePath) !== false) {
                    $fileName = basename($filePath);
                    
                    $mediaRepo = $this->entityManager->getRepository(Media::class);
                    /** @var Media $media */
                    $media = $mediaRepo->findOneBy(['fileName' => $fileName]);
                    
                    if ($media !== null) {
                        // check if media attachment already exists
                        $mediaAttachmentRepo = $this->entityManager->getRepository(MediaAttachment::class);
                        $mediaAttachment = $mediaAttachmentRepo->findOneBy(['media' => $media]);
                        
                        if ($mediaAttachment === null) {
                            $mediaAttachment = new MediaAttachment();
                            $mediaAttachment->setMedia($media);
                            $mediaAttachment->setAttachmentType($attachmentType);
                            $mediaAttachment->setAttachmentId($attachmentId);
                            $this->entityManager->persist($mediaAttachment);
                        }
                    } else {
                        // file exists but no media object associated
                        // most likely a file uploaded before new media manager implementation
                        // todo: likely solution is to handle such cases to where file is moved to proper location, renamed,
                        // with all current references to it updated
                        // the underlying issue is that old references to that media file location will still exist and will
                        // now produce file missing errors
                        // an alternative solution is to have a media manager initialization process where all old media files are moved,
                        // and searches are conducted to find any reference to them and update that reference
                    }
                }
            }
        }
    }

    /**
     * Removes MediaAttachment entities from attachments for media sources that are no longer referenced in the html source.
     * 
     * The html source is parsed for media sources (links and images) and is compared to the full list of MediaAttachment entities
     * that exist for the attachment. Media sources that are no longer referenced in the html source have their corresponding MediaAttachment 
     * entities removed.
     *  
     * @param string $html html source
     * @param string $attachmentType Type of attachment. Ex: 'faq', 'record'
     * @param integer $attachmentId Id of attachment
     * @return void
     */
    public function removeAttachmentFromHTML(string $html, string $attachmentType, int $attachmentId) {
        // Get all current media attachments for the attachment id
        $mediaAttachmentRepo = $this->entityManager->getRepository(MediaAttachment::class);
        /** @var MediaAttachment $mediaAttachments */
        $mediaAttachments = $mediaAttachmentRepo->findBy([
            'attachmentType' => $attachmentType, 
            'attachmentId' => $attachmentId,
        ]);

        // Load the html for parsing
        $html5 = new HTML5([
            'disable_html_ns' => true,
        ]);
        $doc = $html5->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        // Query for all image/link tags and isolate the source attribute
        /** @var \DomNodeList $sources */
        $sources = [];
        $imageSources = $xpath->query('//img/@src');
        $linkSources = $xpath->query('//a/@href');
        
        // Copy into sources array for parsing
        foreach ($imageSources as $imageSource) {
            $sources[] = $imageSource;
        }
        
        foreach ($linkSources as $linkSource) {
            $sources[] = $linkSource;
        }

        // Get all file names from image sources
        $fileNames = [];
        foreach ($sources as $source) {
            $filePath = $source->nodeValue;
            if ($filePath[0] === DIRECTORY_SEPARATOR) {
                // is an absolute file path
                $filePath = join(DIRECTORY_SEPARATOR, [
                    $this->projectDir, 
                    'public',
                    trim($filePath, "/\\"),
                ]);
            }

            if (file_exists($filePath) !== false) {
                $fileName = basename($filePath);
                $fileNames[$fileName] = true;
            }
        }

        // Delete the media attachment for any sources no longer referenced
        foreach ($mediaAttachments as $attachment) {
            $media = $attachment->getMedia();
            $fileName = $media->getFileName();
            if (isset($fileNames[$fileName]) === false) {
                $attachment->setMedia(null);
                $this->entityManager->remove($attachment);
            }
        }
        $this->entityManager->flush();
    }
}