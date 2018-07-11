</main>
<!--Powered by Subjects Plus-->
<div class="container">
    <p style="font-size:.8rem; text-align: center; margin-bottom: .25rem;">Powered by <a href="http://www.subjectsplus.com/" class="no-decoration default">SubjectsPlus</a></p>
</div>

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
            $(this).find('i').removeClass('fa fa-facebook').addClass('fab fa-facebook');
        });

        $('.pluslet[name=\"SubjectSpecialist\"] .staff-details .staff-social[data-show-instagram=\"Yes\"]').each( function() {
            $(this).find('i').removeClass('fa fa-instagram').addClass('fab fa-instagram');
        });

        //Social Media avoid Font Awesome issues
        $('.pluslet[name=\"SocialMedia\"] #social_media_accounts li').each( function() {
            $(this).find('i.fa-facebook-square').removeClass('fa fa-facebook-square').addClass('fab fa-facebook');
            $(this).find('i.fa-twitter-square').removeClass('fa fa-twitter-square').addClass('fab fa-twitter-square');
            $(this).find('i.fa-pinterest-square').removeClass('fa fa-pinterest-square').addClass('fab fa-pinterest-square');
            $(this).find('i.fa-instagram').removeClass('fa fa-instagram').addClass('fab fa-instagram');
        });


    });
</script>

</body>
</html>