<?php
header( 'Content-Type: text/javascript' );
header( 'Cache-control: private' );
header( 'Expires: ' . gmdate( 'D, d M Y H:i:s \G\M\T', time() + 21600 ) );

require_once( __DIR__ . "/functions.php" );

//use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetCollection;
use Assetic\AssetManager;
use Assetic\Asset\AssetReference;
//use Assetic\Filter\JSMinPlusFilter;
//use Assetic\AssetWriter;
use Assetic\Cache\FilesystemCache;
use Assetic\Asset\AssetCache;

// Define the locations for the asset and cache directories
$assets = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'assets';
$cache  = $assets . DIRECTORY_SEPARATOR . 'cache';


$am = new AssetManager();

// Create asset refrences to jQuery and all the other js files in the JS folder
// Creating a refernce to jQuery will allow us to put it first in the file


$am->set( 'otherjs', new AssetCache(
	new GlobAsset( $assets . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . '*.js' )

	, new FilesystemCache( $cache )

) );


$am->set( 'guidejs', new AssetCache(
	new GlobAsset( $assets . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'guides' . DIRECTORY_SEPARATOR . '*.js' )

	, new FilesystemCache( $cache )

) );


$js = new AssetCollection(

	array(
		new AssetReference( $am, 'otherjs' ),
		new AssetReference( $am, 'guidejs' )
	)

);

echo $js->dump();

 