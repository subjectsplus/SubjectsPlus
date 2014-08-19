<?php
    header('Content-Type: text/javascript');
    header('Cache-control: private');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 21600));

//header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
//header('Pragma: no-cache'); // HTTP 1.0.
//header('Expires: 0'); // Proxies.

    require_once(__DIR__ . "/functions.php");
    //require_once(__DIR__ . DIRECTORY_SEPARATOR . "autoloader.php");



    use Assetic\Asset\FileAsset;
    use Assetic\Asset\GlobAsset;
    use Assetic\Asset\AssetCollection;
    use Assetic\AssetManager;
    use Assetic\Asset\AssetReference;
    use Assetic\Filter\JSMinPlusFilter;
    use Assetic\AssetWriter;
    use Assetic\Cache\FilesystemCache;
    use Assetic\Asset\AssetCache;

    // Define the locations for the asset and cache directories
    $assets = dirname(dirname (__DIR__)) . DIRECTORY_SEPARATOR . 'assets';
    $cache = $assets . DIRECTORY_SEPARATOR . 'cache';


    $am = new AssetManager();

    // Create asset refrences to jQuery and all the other js files in the JS folder
    // Creating a refernce to jQuery will allow us to put it first in the file

    $am->set('jquery', new AssetCache(
             new FileAsset($assets . DIRECTORY_SEPARATOR . 'jquery' . DIRECTORY_SEPARATOR . 'jquery-1.11.1.min.js')
             ,new FilesystemCache($cache)

             ));

    $am->set('jquery_ui', new AssetCache(
                                      new FileAsset($assets . DIRECTORY_SEPARATOR . 'jquery' . DIRECTORY_SEPARATOR . 'jquery-ui-1.9.2.min.js')
                                      ,new FilesystemCache($cache)

                                      ));

    $am->set('otherjs', new AssetCache(
             new GlobAsset($assets . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR .  '*.js')

             ,new FilesystemCache($cache)

             ));

    //include guide.js if reffer is from guide.php

//if (isset( $_SERVER['HTTP_REFERER'])) {

//	$lobjSplit = explode( '/', $_SERVER['HTTP_REFERER']);
  //  if( strpos($lobjSplit[count($lobjSplit) - 1], 'guide.php') !== FALSE && $lobjSplit[count($lobjSplit) - 2] == 'guides' )
    //{
    	$am->set('guidejs', new AssetCache(
             new GlobAsset($assets . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR .  'guides' . DIRECTORY_SEPARATOR . '*.js')

             ,new FilesystemCache($cache)

            ));
 //   }
//} else { }
    // Apply the JSMinPlus filter to all the files
    $jquery = new AssetCollection(array (new AssetReference($am, 'jquery')) );
    $jquery_ui = new AssetCollection(array (new AssetReference($am, 'jquery_ui')) );
    $other_js = $am->has('guidejs') ? new AssetCollection(array (new AssetReference($am, 'otherjs'), new AssetReference($am, 'guidejs')))
    	: new AssetCollection(array (new AssetReference($am, 'otherjs')));

    // Place jQuery first in the final output
    $javascripts = new AssetCollection(array ($jquery, $jquery_ui, $other_js ));





    echo $javascripts->dump();
