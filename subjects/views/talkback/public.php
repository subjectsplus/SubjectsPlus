<?php ?>
<div class="pure-g">
    <div class="pure-u-1">
        <div id="content_roof"></div> <!-- end #content_roof -->
        <div id="shadowkiller"></div> <!--end #shadowkiller-->

        <div id="body_inner_wrap">

            <script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
            <script>
                function onSubmit(token) {
                    document.getElementById("tellus").submit();
                }
            </script>

            <br>
            <div class="pure-g">
                <div class="pure-u-1 pure-u-lg-2-3">
                    <div class="pluslet_simple no_overflow">
						<?php print _( "<p><strong>Talk Back</strong> is where you can <strong>ask a question</strong> or <strong>make a suggestion</strong> about library services.</p>
		<p>So, please let us know what you think, and we will post your suggestion and an answer from one of our helpful staff members.</p>" ); ?>
                    </div>
                    <div class="pluslet_simple no_overflow">
                        <h2>Comments from 2019 <span style="font-size: 11px; font-weight: normal;"><a
                                        href="talkback.php?t=prev&amp;v=main">See previous years</a></span></h2>

                        <?php
                        var_dump($comments);
                        ?>

                        <div class="tellus_item oddrow">

                            <a name="5"></a>

                            <p class="tellus_comment"><span class="comment_num">1</span> <strong>testing
                                    default</strong><br>
                                <span style="clear: both;font-size: 11px;">Comment on <em>Apr 12 2019</em></span></p>
                            <br>

                            <p><img src="http://uml-e-spmed.local/assets/users/_charlesbrownroberts/headshot.jpg"
                                    alt="charlesbrownroberts@miami.edu" title="charlesbrownroberts@miami.edu" width="70"
                                    class="staff_photo" align="left">working</p>
                            <p style="clear: both;font-size: 11px;">Answered by <a
                                        href="staff_details.php?name=charlesbrownroberts">Super Admin</a>, SubjectsPlus
                                Admin</p></div>

                        <div class="tellus_item oddrow">

                            <a name="4"></a>

                            <p class="tellus_comment"><span class="comment_num">2</span> <strong>testing default
                                    theme</strong><br>
                                <span style="clear: both;font-size: 11px;">Comment on <em>Apr 12 2019</em></span></p>
                            <br>

                            <p><img src="http://uml-e-spmed.local/assets/users/_charlesbrownroberts/headshot.jpg"
                                    alt="charlesbrownroberts@miami.edu" title="charlesbrownroberts@miami.edu" width="70"
                                    class="staff_photo" align="left">still testing</p>
                            <p style="clear: both;font-size: 11px;">Answered by <a
                                        href="staff_details.php?name=charlesbrownroberts">Super Admin</a>, SubjectsPlus
                                Admin</p></div>
                    </div>
                </div>
                <div class="pure-u-1 pure-u-lg-1-3">
                    <!-- start pluslet -->
                    <div class="pluslet">
                        <div class="titlebar">
                            <div class="titlebar_text">Tell Us What You Think</div>
                        </div>
                        <div class="pluslet_body">
                            <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <strong>Wait! Do you need help right now?</strong><br>Visit the Research Desk! </p>
                            <br>

                            <?php include_once 'comment_form.php'; ?>
                        </div>
                    </div>
                    <!-- end pluslet -->
                    <br>

                </div>
            </div>
            <!-- END BODY CONTENT -->

        </div> <!--end #body_inner_wrap-->
    </div> <!--end pure-u-1-->
</div>
