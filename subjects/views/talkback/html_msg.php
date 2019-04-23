<?php
/**
 * Created by IntelliJ IDEA.
 * User: cbrownroberts
 * Date: 2019-04-23
 * Time: 09:15
 */
?>
<html>
<body style="margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#d4d4d4" style="height: 100%;">
	<tr>
		<td valign="top" align="center">
			<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="width:600px; height:auto;" border="0">
				<tr>
					<td width="600" height="40" valign="top" bgcolor="#d4d4d4">&nbsp;</td>
				</tr>
				<tr>
					<td width="600" height="120" valign="middle" align="center" bgcolor="#FFFFFF">
						<p style="font-size:28px; color:#444; font-family:Helvetica, sans-serif;">
							<?php echo _( "New Comment Awaits Response" ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<td width="600" height="60" valign="top" align="center" bgcolor="#FFFFFF">
						<table width="600" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
							<tr>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
								<td width="50" valign="top" height="40" bgcolor="#FFFFFF">
									<img src="https://sp.library.miami.edu/assets/images/email/calendar.jpg" width="40"
									     height="40" border="0">
								</td>
								<td width="150" valign="bottom" height="40" bgcolor="#FFFFFF">
									<p style="font-size:22px; color:#444; font-family:Helvetica, sans-serif;">
										<?php echo _( "Received:" ); ?></p>
								</td>
								<td width="380" valign="bottom" height="40" bgcolor="#FFFFFF">
									<p style="font-size:22px; color:#858585; font-family:Helvetica, sans-serif;">
										<?php echo $month . " " . $mday . " " . $year; ?></p>
								</td>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="600" height="60" valign="top" align="center" bgcolor="#FFFFFF">
						<table width="600" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
							<tr>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
								<td width="50" valign="top" height="40" bgcolor="#FFFFFF">
									<img src="https://sp.library.miami.edu/assets/images/email/contact.jpg" width="40"
									     height="40" border="0">
								</td>
								<td width="150" valign="bottom" height="40" bgcolor="#FFFFFF">
									<p style="font-size:22px; color:#444; font-family:Helvetica, sans-serif;">
										<?php echo _( "Contact:" ) ?></p>
								</td>

								<td width="380" valign="bottom" height="40" bgcolor="#FFFFFF">
									<p style="font-size:22px; color:#858585; font-family:Helvetica, sans-serif;">
										<?php echo scrubData( $this_name ); ?></p>
								</td>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="600" height="65" valign="top" align="center" bgcolor="#FFFFFF">
						<table width="600" height="40" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
							<tr>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
								<td width="50" valign="top" height="40" bgcolor="#FFFFFF">
									<img src="https://sp.library.miami.edu/assets/images/email/comment.jpg" width="40"
									     height="40" border="0">
								</td>
								<td width="530" valign="middle" height="40" bgcolor="#FFFFFF">
									<p style="font-size:22px; color:#444; font-family:Helvetica, sans-serif;">
										<?php echo _( "Comment:" ); ?>
									</p>
								</td>
								<td width="10" valign="top" height="40" bgcolor="#FFFFFF">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="600" valign="top" align="center" bgcolor="#FFFFFF">
						<table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
							<tr>
								<td width="60" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
								<td width="530" valign="top" bgcolor="#FFFFFF">
									<p style="font-size:20px; color:#858585; font-family:Helvetica, sans-serif;">
										<?php echo scrubData( $this_comment ); ?>
									</p>
								</td>
								<td width="10" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="600" height="60" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
				</tr>
				<tr>
					<td width="600" height="50" valign="top" align="center" bgcolor="#FFFFFF">
						<table width="600" height="50" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
							<tr>
								<td width="175" height="50" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
								<td width="250" height="50" valign="middle" align="center" bgcolor="#858585">
									<p style="font-size:28px; color:#FFF; font-family:Helvetica, sans-serif;"><a
											href="https:sp.library.miami.edu/control/talkback" target="_blank"
											style="color:#FFF; text-decoration:none;">
                                            <span style="color: #FFF; text-decoration:none;">
                                                <?php echo _( "Reply Now" ); ?>
                                            </span>
										</a>
									</p>
								</td>
								<td width="175" height="50" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="600" height="30" valign="bottom" align="center" bgcolor="#FFFFFF">
						<p style="font-size:14px; color:#858585; font-family:Helvetica, sans-serif;">
							<?php _( "You will be required to log in" ); ?></p>
					</td>
				</tr>
				<tr>
					<td width="600" height="100" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
				</tr>
				<tr>
					<td width="600" height="70" valign="middle" align="center" bgcolor="#FFFFFF">
						<img src="https://sp.library.miami.edu/assets/images/email/subjectsplus-footer.jpg" width="276"
						     height="40" border="0">

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
