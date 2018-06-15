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

<script>
    $( function(){

        //Print scripts
       function showPrintDialog() {
            $(".printer_tabs").colorbox({
                html: "<h3>Print Selection</h3><ul class=\"list-unstyled\"><li><a onclick=\"window.print();\" class=\"btn btn-default\" href=\"#\">Print Current Tab</a></li><li><a id=\"all-tabs\" class=\"btn btn-default\" href=\"#\">Print All Tabs</a></li></ul>",
                innerWidth:280,
                innerHeight:300,
                onComplete:function() {
                    // Print all tabs
                    $('#all-tabs').click( function() {

                        $('#tab-body').children().each( function () {
                            if ($(this).is(":visible")) {
                                $(this).addClass('current-visible-tab');
                            }
                            $(this).show();
                        });

                        window.print();
                        console.log("print them puppies");

                        $('#tab-body').children().each( function () {
                            $(this).hide();
                            $('.current-visible-tab').show();
                        });

                        $('.current-visible-tab').removeClass('current-visible-tab');
                    });
                }
            });
        }

        // show print dialog box for multi-tab
        $('.printer_tabs').click( function() {
            showPrintDialog();
        });

        $('.printer_no_tabs').click( function() {
            window.print();
        });

        // Select2 for Guide Tabs
        $('#select_tabs').select2({
            width: "80%",
            containerCssClass: "tabs-select",
            dropdownCssClass: "tabs-select-dropdown"
        });

        $("#select_tabs").change(function() {
        console.log("patty");

        // open external link on tab-select
        var option_external_link = $(this).find('option:selected').attr('data-external-link');
        console.log(option_external_link);
        if (option_external_link != "") {
            window.open(option_external_link, '_blank');
        }

        var option_tab_link = $(this).find('option:selected').val();
        console.log(option_tab_link);

        //hide all tab body children
        var tabchild = $('#tab-body .sptab');

        var active_tabbody = tabchild.attr('id');
        console.log(active_tabbody);

        var trim = active_tabbody.substr(1);


        if (trim == option_tab_link){
            console.log("we match");
            $(tabchild).show();

        }


        //var activeTab = $(this).val();
        //$(activeTab).show();
        });

    });
</script>

</body>
</html>