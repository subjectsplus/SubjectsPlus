<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php print $page_title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="Description" content="<?php if (isset($description)) {print $description;} ?>" />
<meta name="Keywords" content="<?php if (isset($keywords)) {print $keywords;} ?>" />
<meta name="Author" content="" />
<?php if (isset($is_responsive) && $is_responsive == TRUE) {
    echo '<link type="text/css" media="all" rel="stylesheet" href="' . $AssetPath . 'css/bootstrapz.css">';
}
?>

<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/shared/pure.css">
<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/public/v3.css">
<!-- <link type="text/css" media="print" rel="stylesheet" href="<?php print $AssetPath; ?>css/print.css"> -->

<?php // Load our jQuery libraries + some css
if (isset($use_jquery)) { print generatejQuery($use_jquery);
?>

<?php
}

if (!isset ($noheadersearch)) { 
    
    $search_form = '
            <div class="autoC" id="autoC">
                <form id="sp_admin_search" class="pure-form" method="post" action="' . getSubjectsURL() . 'search.php">
                <input type="text" placeholder="Search" autocomplete="off" name="searchterm" size="" id="sp_search" class="ui-autocomplete-input autoC"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                <input type="submit" alt="Search" name="submitsearch" id="topsearch_button" class="pure-button pure-button-topsearch" value="Go">
                </form>
            </div>    ';
} else {
    $search_form = '';
}


?>
</head>

<body>

<div id="header"> 
    <div id="header_inner_wrap">
    <div class="pure-g-r">
        <div class="pure-u-3-4">
            <img src="../assets/images/public/logo.png" alt="Home Page" />
        </div>

        <div class="pure-u-1-4">
        <?php print $search_form; ?>
        </div>

    </div>
    </div>
    </div>
</div>


<div class="pure-g-r header2">
    <div class="wrapper-full">
        <div class="pure-u-1">
        <h1><?php print $page_title; ?></h1>
<!--
        </div>
    </div>

</div>

<div class="container-fluid" id="main-content">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">

            -->