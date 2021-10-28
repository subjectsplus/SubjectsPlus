<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileNamerService
{
    private const MAX_EXTENSION_CHAR_COUNT = 10;

    public function directoryName(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        return $this->getSubDirectoryFromMimeType($mimeType);
    }

    public function getSubDirectoryFromMimeType(string $mimeType): string
    {
        $subDir = "";
        
        if (strpos($mimeType, "image/") !== false) {
            $subDir .= "images";
        } else if (strpos($mimeType, "video/") !== false) {
            $subDir .= "videos";
        } else if ($mimeType === "application/pdf") {
            $subDir .= "pdfs";
        } else {
            $subDir .= "other";
        }
        return $subDir;
    }

    public function fileName(UploadedFile $file): string
    {
        $origName = $file->getClientOriginalName();
        $origBaseName = pathinfo($origName, PATHINFO_BASENAME);
        $origFileName = pathinfo($origBaseName, PATHINFO_FILENAME);
        $extension = pathinfo($origBaseName, PATHINFO_EXTENSION);
        $uuid = str_replace('.', '', uniqid('-', true));

        // if extension is too long, shorten it
        if (strlen($extension) > self::MAX_EXTENSION_CHAR_COUNT) {
            $extension = substr($extension, 0, self::MAX_EXTENSION_CHAR_COUNT);
        }
        
        // max file name character count, accounting for extension, uuid, and dot character
        $maxFileName = 255 - strlen($extension) - strlen($uuid) - 1;
        
        // if file name is longer than the space left, shorten it
        if (strlen($origFileName) > $maxFileName) {
            $origFileName = substr($origFileName, 0, $maxFileName);
        }

        $uniqExtension = sprintf('%s.%s', $uuid, $extension);
        $fileName = sprintf('%s%s', $origFileName, $uniqExtension);

        return $fileName;
    }
}