<?php

namespace App\Service;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use App\Entity\Media;

class DirectoryNamerService implements DirectoryNamerInterface
{
    public function directoryName($object, PropertyMapping $mapping): string
    {
        if ($object instanceof Media) {
            $mimeType = $object->getMimeType();
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
        } else {
            throw new \Exception("Error: Object is not of type 'Media'; unable to name directory for file uploaded.");
        }
    }
}