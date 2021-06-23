<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LegacyBridge
{
    public static function prepareLegacyScript(Request $request, Response $response, string $publicDirectory)
    {

        // If Symfony successfully handled the route, you do not have to do anything.
        if (false === $response->isNotFound()) {
            return;
        }

        // Figure out how to map to the needed script file
        // from the existing application and possibly (re-)set
        // some env vars.
        $legacyScriptFilename = '..'.$request->getPathInfo();

        if (is_file($legacyScriptFilename)) {
            return $legacyScriptFilename;
        } else {
            return $legacyScriptFilename.'/index.php';
        }

        return $legacyScriptFilename;
    }
}
