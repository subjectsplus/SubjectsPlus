</main>
</div>

<!--Load Jekyll-built site footer (Site Footer, Site Toolbar)-->
<?php include("includes/jekyll-site-footer.php"); ?>

<!--container for side navigation contents-->
<div class="c-offcanvas is-hidden" id="left">
    <div class="navbar mobile">
        <div class="offcanvas-nav d-block d-xl-none" data-set="bs"></div>
    </div>
</div>

</div>

<!--Load Jekyll-built site scripts (Site Header Scripts, Mega Menus Scripts, Component Scripts)-->
<?php include("includes/jekyll-site-scripts.php"); ?>

<!-- Print scripts-->
<script>
    $( function(){
        // Basic print function
        function printView() {
            var $visible_tab;

            $('#tab-body').children().each(function () {
                if ($(this).is(":visible")) {
                    $visible_tab = $(this);
                }
                else {
                    $(this).show();
                }
            });
            window.print();

            $('#tab-body').children().each(function () {
                $(this).hide();
            });

            $(visible_tab).show();
        }

        function showPrintDialog() {
        $(".printer_tabs").colorbox({html: "<h3>Print Selection</h3><ul class=\"list-unstyled\"><li><a onclick=\"window.print();\" class=\"no-decoration\">Print Current Tab</a></li><li><a onclick=\"printView();\" class=\"no-decoration\">Print All Tabs</a></li></ul>", innerWidth:280, innerHeight:300});
        }

        // show print dialog box for multi-tab
        $('.printer_tabs').click( function() {
            showPrintDialog();
        });

        $('.printer_no_tabs').click( function(){ window.print(); });
    });
</script>

</body>
</html>