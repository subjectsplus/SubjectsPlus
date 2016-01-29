<?php

$subcat = "firsttime";
$page_title = "First Time Update";

$use_jquery = array("ui_styles");
$no_header = "yes";

include("header.php");

?>
	<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
<div class="box" align="center">
    <h2 class="bw_head"><?php echo _( "First Time Update" ); ?></h2>

			<p><?php echo _( "Welcome to your new version of SubjectsPlus" ) ?></p>
			<div align="left">
				<ul>
					<li><?php echo _( 'Configure SubjectsPlus ' ) ?><a href="../edit-config.php" target="_blank"><?php echo _( 'here' ) ?></a>.</li>
					<ul>
						<li><?php echo _( 'You can change the email key so you can log in with only the username.' ) ?></li>
						<li><?php echo _( 'You can configure your Catalog Connections as they were in the previous version.' ) ?></li>
						<li><?php echo _( 'Move over your previous /users/ folders to /assets/, i.e., _jsmith' ) ?></li>
						<li><?php echo _( 'If you have styled the front end, move over default.css to assets/css/ and tweak as necessary.  See wiki for tips.' ) ?></li>
					</ul>
					<li><a href="http://www.subjectsplus.com/wiki/index.php?title=What%27s_New_in_3.0"><?php echo _( 'You can find out new features in 3.0 on the wiki.' ) ?></a></li>
				</ul>
			</div>
		</div>
	</div>
<?php

include("footer.php")

?>