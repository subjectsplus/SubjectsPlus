<?php
    
    require_once dirname(dirname(__DIR__)).'/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';
    
    use Symfony\Component\ClassLoader\UniversalClassLoader;
    
    $loader = new UniversalClassLoader();
   
    
    $loader->registerNamespace('Assetic',  dirname(dirname(__DIR__)) . '/lib');
    $loader->registerNamespace('SubjectsPlus',  dirname(dirname(__DIR__)) . '/lib');
    $loader->registerNamespace('CSSMin',  dirname(dirname(__DIR__)) . '/lib');
    $loader->registerNamespace('RichterLibrary', dirname(dirname(__DIR__)) . '/lib');
    $loader->registerNamespace('HTMLPurifier', dirname(dirname(__DIR__)) . '/lib');
    
    $loader->register();
?>