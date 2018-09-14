<?php
/**
 * Created by PhpStorm.
 * User: cbrownroberts
 * Date: 9/28/16
 * Time: 10:15 AM
 */

?>

<!--GA Tracker Code-->
<script type="text/javascript">

    var uaCode = $('#google-analytics-ua').attr('data-uacode');
    //console.log(uaCode);

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', uaCode]);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
