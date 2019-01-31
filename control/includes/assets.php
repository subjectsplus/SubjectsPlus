<?php 


$assets =  dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

$js = new AssetCollection(array(
    new GlobAsset($assets . 'jquery/*')
));

$css = new AssetCollection(array(
    new GlobAsset($assets . 'css/*')
));

 ?>
<script>
<?php // echo $js->dump(); ?>
</script>
<style>
<?php // echo $css->dump(); ?>
</style>