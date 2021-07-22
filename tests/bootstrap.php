<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
    print("\nRequired /config/bootstrap.php\n");
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    $dotEnv = new Dotenv();
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
    $dotEnv->populate([
        'APP_ENV' => 'dev',
        'KERNEL_CLASS' => 'App\Kernel',
        // ...
    ]);
    print("\nLoaded env: " . dirname(__DIR__)."/.env\n");

}
