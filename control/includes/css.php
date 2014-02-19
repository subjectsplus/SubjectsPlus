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
    
    $am->set('pure', new AssetCache(
                                        new FileAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'pure.css')
                                        ,new FilesystemCache($cache)
                                        
                                        ));
    
    $am->set('jqueryui', new AssetCache(
                                     new FileAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'jquery-ui.css')
                                     ,new FilesystemCache($cache)
                                     
                                     ));
    
    $am->set('colorbox', new AssetCache(
                                     new FileAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'colorbox.css')
                                     ,new FilesystemCache($cache)
                                     
                                     ));
    
    $am->set('guide', new AssetCache(
             new FileAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . 'guide.css')
             ,new FilesystemCache($cache)
             
                                     ));
    
    
    
    
    $am->set('css', new AssetCache(new GlobAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR .  '*.css'), new FilesystemCache($cache)));
    
    
    // Apply the CSSMinFilter
    $pure = new AssetCollection(array (new AssetReference($am, 'pure')));
    $guide = new AssetCollection(array (new AssetReference($am, 'guide')));
    $jqueryui = new AssetCollection(array (new AssetReference($am, 'jqueryui')));
    $colorbox = new AssetCollection(array (new AssetReference($am, 'colorbox')));
    $css_files = new AssetCollection(array (new AssetReference($am, 'css')));
    
    
    
    // Create an AssetCollection that uses the newly minified css
    
    $css = new AssetCollection(array ($pure, $colorbox, $guide,  $jqueryui,  $css_files) );
    
    
    // Tell the browser that this is CSS and that it should be cached

    header('Cache-control: public');
    header('Content-Type: text/css');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 21600));
    
    
    echo $css->dump();
    
    ?>