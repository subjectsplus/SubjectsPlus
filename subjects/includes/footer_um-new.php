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
            dropdownCssClass: "tabs-select-dropdown",
            placeholder: "In this guide..."
        });

        $("#select_tabs").change(function() {

            // open external link on tab-select
            var option_external_link = $(this).find('option:selected').attr('data-external-link');
            console.log(option_external_link);
            if (option_external_link != "") {
                window.open(option_external_link, '_blank');
            } else {
                var option_tab_link = $(this).find('option:selected').val();
                console.log(option_tab_link);
                var trim = option_tab_link.substr(6);
                console.log(trim);
                $("#tabs").tabs("option", "active", trim);
            }
        });

        //fix FOUC
        $('#tab-container').attr('style', 'visibility:visible;');

        //Add style to Pluslet Feature
        $('.ts-feature').parent().parent().addClass('pluslet-feature');

        //add icon for external tabs - desktop
        $('#tab-container .ui-tabs-nav li').each( function() {
            var external_tab = $(this).attr('data-external-link');

            if (external_tab != "") {
                $(this).find('a').append('<i class=\"fas fa-external-link-alt\" title=\"Opens in new window\"></i>');
                console.log(external_tab);
            }
        });

        //Experts Pluslet - move tooltip into details
        $('.pluslet[name=\"Experts\"] .expert-list-circ li').each( function() {
            var expert_pluslet_tooltip = $(this).find('.expert-tooltip');
            $(this).find('.expert-label').append(expert_pluslet_tooltip);
        });

        //Subject Specialist Social Icons substitution to avoid Font Awesome issues
        $('.pluslet[name=\"SubjectSpecialist\"] .staff-details .staff-social[data-show-twitter=\"Yes\"]').each( function() {
            $(this).find('i').addClass('fab fa-twitter-square').removeClass('fa fa-twitter');
        });

        $('.pluslet[name=\"SubjectSpecialist\"] .staff-details .staff-social[data-show-pinterest=\"Yes\"]').each( function() {
            $(this).find('i').addClass('fab fa-pinterest-square').removeClass('fa fa-pinterest');
        });

        $('.pluslet[name=\"SubjectSpecialist\"] .staff-details .staff-social[data-show-facebook=\"Yes\"]').each( function() {
            $(this).find('i').addClass('fab fa-facebook').removeClass('fa fa-facebook');
        });


    });
</script>

</body>
</html>