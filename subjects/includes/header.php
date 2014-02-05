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
    echo '<link type="text/css" media="all" rel="stylesheet" href="' . $AssetPath . 'css/bootstrap.css">';
}
?>

<link type="text/css" media="screen" rel="stylesheet" href="<?php print $AssetPath; ?>css/default.css">
<!-- <link type="text/css" media="print" rel="stylesheet" href="<?php print $AssetPath; ?>css/print.css"> -->

<?php // Load our jQuery libraries + some css
if (isset($use_jquery)) { print generatejQuery($use_jquery);
?>
    <script type="text/javascript" language="javascript">
    // Used for autocomplete

        $.fn.defaultText = function(value){

            var element = this.eq(0);
            element.data('defaultText',value);

            element.focus(function(){
                if(element.val() == value){
                    element.val('').removeClass('defaultText');
                }
            }).blur(function(){
                if(element.val() == '' || element.val() == value){
                    element.addClass('defaultText').val(value);

                }
            });

            return element.blur();
        }
    </script>
<?php
}
?>
</head>

<body>

<div id="header"> 
    <div id="header_inner_wrap">
  	<div><h1><?php print $page_title; ?></h1></div>
	<div id="header-tools" style="float: right;"><!-- AddThis Button BEGIN  -->
	  <a href="http://www.addthis.com/bookmark.php?v=250&pub=xa-4a5cb30866f1511b" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s7.addthis.com/static/btn/lg-bookmark-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a5cb30866f1511b"></script>
	  <!--AddThis Button END -->
	</div>
    </div>
</div>
<div class="container-fluid" id="main-content">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">