<?php

$subcat = "firsttime";
$page_title = "First Time Install";

$use_jquery = array("ui_styles");
$no_header = "yes";

include("header.php");

?>
	<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
		<h2 class="bw_head"><?php echo _( "First Time Installation " ); ?></h2>
		<div class="box" align="center">
			<p><?php echo _( 'Here are a few tips for setting up your SujectsPlus installation.' ) ?></p>
			<div align="left">
				<ul>
					<li><?php echo _( 'Change your default password to something more memorable.' ) ?></li>
					<li><?php echo _( 'Configure SubjectsPlus ' ) ?><a href="../edit-config.php" target="_blank"><?php echo _( 'here' ) ?></a>.</li>
				</ul>
			</div>
		</div>
	</div>
<?php

include("footer.php")

?>