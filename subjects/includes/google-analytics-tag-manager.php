<?php
/**
 * Created by PhpStorm.
 * User: pvillanueva
 * Date: 06/05/18
 */
?>

<!-- Google Analytics Tracker Script Code-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', '<?php print $google_analytics_ua; ?>', 'auto');
        ga('send', 'pageview');
    });
</script>

<script>
    dataLayer = [];
</script>

<!-- Google Analytics Tag Manager Script Code-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php print $google_tag_manager; ?>');
    });
</script>
<!-- End Google Tag Manager -->


