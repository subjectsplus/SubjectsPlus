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
							<?php echo _( "New Problem Reported" ); ?>
						</p>
					</td>
				</tr>
                <tr>
                    <td width="600" height="100" valign="top" bgcolor="#FFFFFF"><?php echo $msg; ?></td>
                </tr>
				<tr>
					<td width="600" height="100" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
				</tr>
				<tr>
					<td width="600" height="70" valign="middle" align="center" bgcolor="#FFFFFF">
						<img src="https://sp.library.miami.edu/assets/images/email/subjectsplus-footer.jpg"
                             width="276"
						     height="40" border="0">

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
