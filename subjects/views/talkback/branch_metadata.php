<?php
if ( isset( $all_tbtags ) ) {

	// Let's get the first item off the tb array to use as our default
	reset( $all_tbtags ); // make sure array pointer is at first element
	$set_filter = key( $all_tbtags );

	// determine branch/filter
	if ( isset( $_REQUEST["v"] ) ) {
		$set_filter = scrubData( lcfirst( $_REQUEST["v"] ) );

		// Quick'n'dirty setup email recipients
		switch ( $set_filter ) {
			case "main":
				$page_title   = _("Comments for Main Library");
				$form_action  = "talkback.php?v=$set_filter";
				$tb_bonus_css = "";
				break;
			default:
				$page_title   = _("Comments for Main Library");
				$form_action  = "talkback.php";
				$tb_bonus_css = "";
		}

		// override our admin email
		if ( isset( $all_tbtags[ $set_filter ] ) && $all_tbtags[ $set_filter ] != "" ) {
			$administrator_email = $all_tbtags[ $set_filter ];
		}

	}
}
