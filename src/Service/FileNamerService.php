<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Media;

class FileNamerService
{
    public function directoryName(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $subDir = "";
        
        if (strpos($mimeType, "image/") !== false) {
            $subDir .= "images/";
        } else if (strpos($mimeType, "video/") !== false) {
            $subDir .= "videos/";
        } else if ($mimeType === "application/pdf") {
            $subDir .= "pdfs/";
        } else {
            $subDir .= "other/";
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
        $uniqExtension = sprintf('%s.%s', $uuid, $extension);
        $fileName = sprintf('%s%s', $origFileName, $uniqExtension);

        // todo: check if within 255 characters
        return $fileName;
    }
}