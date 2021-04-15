<?php

namespace App\Service;

class DatabaseService
{
    public function databaseUrl(string $url, int $accessRestrictionId, string $proxyPrefix = ''): string
    {
        global $proxyURL;

        if (1 == $accessRestrictionId) { // Public resources don't need a proxy
            return $url;
        }

        return $proxyPrefix ? $proxyPrefix.$url : $proxyURL.$url;
    }
}
