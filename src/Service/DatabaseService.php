<?php

namespace App\Service;

class DatabaseService
{

    public function databaseUrl(string $url, int $accessRestrictionId, string $proxyPrefix=''): string
    {
        global $proxyURL;

        if ($accessRestrictionId == 1) // Public resources don't need a proxy
        {
            return $url;
        }

        return $proxyPrefix ? $proxyPrefix . $url : $proxyURL . $url;
    }


}


?>