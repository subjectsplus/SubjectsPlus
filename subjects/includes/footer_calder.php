</main>
<footer class="mt-auto">
	<!--Powered by Subjects Plus-->
	<div class="container mb-4">
		<p style="font-size:.8rem; text-align: center; margin-bottom: .25rem;">Powered by <a href="http://www.subjectsplus.com/" class="no-decoration default">SubjectsPlus</a></p>
	</div>
	<div class="footer-content p-3">
		<div class="container">
			<div class="row no-gutters">
				<div class="col-sm-3 col-md-2 mb-3 mb-md-0">
					<a href="http://www.miami.edu/" target="_blank" title="University of Miami" class="no-decoration">
						<img src="https://www.library.miami.edu/assets/common-images/um-logo2.png" alt="University of Miami" class="footer-logo" />
					</a>
				</div>
				<div class="col-sm-9 col-md-6 mb-3 mb-md-0">
					<h4 class="mb-0"><a href="https://www.library.miami.edu/calder/index.html" class="no-decoration">Louis Calder Memorial Library</a></h4>
					<h5 class="mb-1"><a href="https://www.library.miami.edu" class="no-decoration">University of Miami Libraries</a></h5>
					<p class="mb-0">1601 NW 10th Ave Miami, FL 33136 | <a href="tel:1-305-243-6403" class="no-decoration telephone-link">(305) 243-6403</a></p>
				</div>
				<div class="col-md-4 text-md-right">
					<div>
						<a href="https://www.facebook.com/caldermedlibrary" target="_blank" class="no-decoration" title="Facebook"><i class="fab fa-facebook-square"></i></a>
					</div>
					<p class="mb-0">&copy; <?php echo date('Y'); ?> | <a href="https://welcome.miami.edu/privacy-and-legal/privacy/index.html"  class="no-decoration" target="_blank">Privacy</a> | <a href="mailto:sxh2751@med.miami.edu" target="_blank" class="no-decoration">Report Site Issue</a></p>
				</div>
			</div>
		</div>
	</div>
</footer>
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