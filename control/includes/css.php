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
    
    // A new CSS file can be added by sticking it in the assets folder or in 3 steps you can add a file that needs to be called in a specific order.
    
  
    // Create references to specific files in the assest directory with the AssetManager
    
    $am = new AssetManager();

    
    // Step 1.
    
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
    
    
    // Glob all the rest of the CSS files together
    
    
    $am->set('css', new AssetCache(new GlobAsset($assets . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR .  '*.css'), new FilesystemCache($cache)));
    
    
    
    // Step 2.
    // This is where the CSSMin filter will be applied eventually.
    $pure = new AssetCollection(array (new AssetReference($am, 'pure')));
    $guide = new AssetCollection(array (new AssetReference($am, 'guide')));
    $jqueryui = new AssetCollection(array (new AssetReference($am, 'jqueryui')));
    $colorbox = new AssetCollection(array (new AssetReference($am, 'colorbox')));
    $css_files = new AssetCollection(array (new AssetReference($am, 'css')));
    
    
    
    
    // Step 3.
    // Create an AssetCollection that uses the newly minified css
    $css = new AssetCollection(array ($pure, $colorbox, $guide,  $jqueryui,  $css_files) );
    
    
    // Tell the browser that this is CSS and that it should be cached

    header('Cache-control: public');
    header('Content-Type: text/css');
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 21600));
    
    
    echo $css->dump();
    
    ?>