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
		<td valign="top">
            <table bgcolor="#FFFFFF" style="width:600px; height:auto;" border="0">
                <tr>
                    <th colspan="2"><?php echo _( "New uSearch Problem Reported" ); ?></th>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Date submitted: " );?></td>
                    <td><?php echo $date_submitted;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "From Name: " );?></td>
                    <td><?php echo $user_name;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "From Email: " );?></td>
                    <td><?php echo $user_email;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Affiliation: " );?></td>
                    <td><?php echo $affiliation;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Problem Item: " );?></td>
                    <td><?php echo $item_title;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Problem Permalink: " );?></td>
                    <td><a href="<?php echo $item_permalink;?>"><?php echo $item_permalink;?></a></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Primo View: " );?></td>
                    <td><?php echo $primo_view;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Problem Type: " );?></td>
                    <td><?php echo $problem_type;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Problem Description: " );?></td>
                    <td><?php echo $description;?></td>
                </tr>
                <tr>
                    <td width="20%"><?php echo _( "Box file: " );?></td>
                    <td><a href="<?php echo $box_file; ?>"><?php echo $box_file;?></a></td>
                </tr>
                <tr>
                    <td colspan="2" width="600" height="70" valign="middle" align="center" bgcolor="#FFFFFF">
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

