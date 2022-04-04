<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};



//use App\Kernel;
//use App\LegacyBridge;
//use Symfony\Component\Dotenv\Dotenv;
//use Symfony\Component\ErrorHandler\Debug;
//use Symfony\Component\HttpFoundation\Request;
//
//require dirname(__DIR__).'/vendor/autoload.php';
//include_once(__DIR__ . "/../control/includes/config.php");
//include_once(__DIR__ . "/../control/includes/functions.php");
//
//(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
//
///*
// * The kernel will always be available globally, allowing you to
// * access it from your existing application and through it the
// * service container. This allows for introducing new features in
// * the existing application.
// */
//global $kernel;
//
//if ($_SERVER['APP_DEBUG']) {
//    umask(0000);
//
//    Debug::enable();
//}
//
//$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
//$request = Request::createFromGlobals();
//$response = $kernel->handle($request);
//
///*
// * LegacyBridge will take care of figuring out whether to boot up the
// * existing application or to send the Symfony response back to the client.
// */
//$scriptFile = LegacyBridge::prepareLegacyScript($request, $response, __DIR__);
//if ($scriptFile !== null) {
//    require $scriptFile;
//} else {
//    $response->send();
//}
//
//$kernel->terminate($request, $response);
