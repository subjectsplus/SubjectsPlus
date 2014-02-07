<?php
    require_once(__DIR__ . "/autoloader.php");
    
    use Assetic\Asset\FileAsset;
    use Assetic\Asset\GlobAsset;
    use Assetic\Asset\AssetCollection;
    use Assetic\AssetManager;
    use Assetic\Asset\AssetReference;
    use Assetic\Filter\CSSMinFilter;
    use Assetic\AssetWriter;
    use Assetic\Cache\FilesystemCache;
    use Assetic\Asset\AssetCache;
 
    // Define the asset and cache directories
    
    $assets = dirname(dirname (__DIR__)) . DIRECTORY_SEPARATOR . 'assets';
    $cache = $assets . DIRECTORY_SEPARATOR . 'cache';
    
    // Create a reference to all the CSS files in the asset directory
    
    $am = new AssetManager();
    $am->set('css', new AssetCache(new GlobAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR .  '*.css'), new FilesystemCache($cache)));
    
    
    // Apply the CSSMinFilter
    $css_files = new AssetCollection(array (new AssetReference($am, 'css')));
    
    
    // Create an AssetCollection that uses the newly minified css
    
    $css = new AssetCollection(array ($css_files) );
    
    
    // Tell the browser that this is CSS and that it should be cached

    header('Cache-control: public');
    header('Content-Type: text/css');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 21600));
    
    
    echo $css->dump();
    
    ?>