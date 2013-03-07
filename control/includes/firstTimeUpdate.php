<?php

$subcat = "firsttime";
$page_title = "First Time Update";

$use_jquery = array("ui_styles");
$no_header = "yes";

include("header.php");

?>
	<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
		<h2 class="bw_head"><?php echo _( "First Time Update" ); ?></h2>
		<div class="box" align="center">
			<p><?php echo _( 'Here are a few notes regarding the new version of SubjectsPlus.' ) ?></p>
			<div align="left">
				<ul>
					<li><?php echo _( "Welcome to version 2.0." ) ?></li>
					<li><?php echo _( 'Configure SubjectsPlus ' ) ?><a href="../edit-config.php" target="_blank"><?php echo _( 'here' ) ?></a>. <?php echo _( 'This is where you can change the email key so you can log in with only the username' ) ?>.</li>
					<li><?php echo _( 'Here are notes about update!' ) ?></li>
				</ul>
			</div>
		</div>
	</div>
<?php

include("footer.php")

?>