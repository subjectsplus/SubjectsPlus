<?php
namespace SubjectsPlus\Control;
/**
 *   @file sp_LinkChecker
 *   @brief manage link checking
 *
 *   @author rgilmour, gspomer, dgonzalez
 *   @date Apr 2013
 */


class LinkChecker {

	private $_proxy;
	private $_timeout;
	private $_extra;

	public function __construct( $lstrProxy = '', $lintTimeout = 5, $useFlush = TRUE )
	{
		//set timeout to zero because link checker can take a very long time
		ini_set('max_execution_time', '0' );

		if (!function_exists('curl_exec')) throw new \Exception('CURL extension required to create an sp_LinkChecker object');

		$this->_proxy = $lstrProxy;
		$this->_timeout = $lintTimeout;
		$this->_extra = array();

		if($useFlush) ob_start();
	}

	/**
	 * sp_LinkChecker::displayHTMLForm() - display link checker form depending on type
	 *
	 * @param string $lstrType
	 * @return void
	 */
	public function displayHTMLForm( $lstrType = "" )
	{
		if( $lstrType == "" ) return;

		global $AssetPath;

		if( isset($_GET['wintype']) && $_GET['wintype'] == 'pop')
		{
			?>
			<div id="maincontent">
			<?php
		}
		?>

		<div class="pure-g">
  			<div class="pure-u-2-3">

  			    <div class="pluslet">
			      <div class="titlebar">
			        <div class="titlebar_text"><?php print _("Link Checker"); ?></div>
			        <div class="titlebar_options"></div>
			      </div>
			      <div class="pluslet_body">
		<?php
		if ($lstrType == 'record')
		{
			?>
			<span class="record_label"><?php print _("Records"); ?>: (<em style="font-style: italic; font-size: smaller;"><?php echo _( "This may take a while. Be patient." ); ?></em>)</span><br />
				<form method="post">
					<input type="submit" class="button" name="LinkCheckRecords" value="<?php print _("Check Links In Records"); ?>" />
			<?php
		}
		if ($lstrType == 'subject')
		{
			?>
			<span class="record_label"><?php print _("Select Guide"); ?>:</span><br />
				<form method="post">
			<?php
			if($_SESSION['admin'] == 1)
			{
				$from = 'subject';
				$where = '';
			}
			else
			{
				$from = 'subject, staff_subject';
				$where = "WHERE subject.subject_id = staff_subject.subject_id " .
							"AND staff_subject.staff_id = $_SESSION[staff_id]";
			}
			$guide_select = "SELECT subject, subject.subject_id FROM $from $where ORDER BY subject ASC";

            $db = new Querier;
                $guide_result = $db->query($guide_select);
			$guide_list = array();

			foreach($guide_result as $guide_data)
			{
				$guide_list[] = array( $guide_data['subject_id'], $guide_data['subject'] );
			}

			$lintCurrentSubjectID = isset( $_REQUEST['subject_id'] ) ? $_REQUEST['subject_id'] : '';
			$lobjDropDown = new Dropdown( 'subject_id', $guide_list, $lintCurrentSubjectID, "50" );
			echo $lobjDropDown->display();
			?>
					
					<input type="submit" class="button pure-button pure-button-primary" value="<?php print _("Check Links In Guide"); ?>" />
			<?php
		}
		?>
				</form>
      </div>
    </div>
  </div>


  <div class="pure-u-1-3">
    <div class="pluslet">
      <div class="titlebar">
        <div class="titlebar_text"><?php print _("Legend"); ?></div>
        <div class="titlebar_options"></div>
      </div>
      <div class="pluslet_body">
				<i class="fa fa-check" title="<?php print _("Image: OK") ?>\"></i> = <?php print _("Link is good!"); ?><br /><br />
				<i class="fa fa-exclamation-triangle" title="<?php print _("Image: Uh oh") ?>\"></i> = <?php print _("<i>Possible</i> problem with link; click on link to open in new window."); ?><br />
			</div>

			    </div>
  </div>
		<?php
	}

	/**
	 * sp_LinkChecker::checkSubjectLinks() - link checker for specific subject id
	 *
	 * @param int $lintSubjectID
	 * @return void
	 */
	public function checkSubjectLinks( $lintSubjectID = "" )
	{
		if( $lintSubjectID == "" ) return;

		global $AssetPath;
		global $CpanelPath;

		$db = new Querier();

		$this->setShortForm( $lintSubjectID );

		?>
		<script>
		// Hide loading message/image when page is fully loaded.
		$(document).ready(function() {
			$('#loading').html('<?php echo $this->getMailOptions();?>');
		});
		</script>

				<div class="pure-g">
  			<div class="pure-u-2-3">
		  			    <div class="pluslet">
			      <div class="titlebar">
			        <div class="titlebar_text"><?php print _("Results"); ?></div>
			        <div class="titlebar_options"></div>
			      </div>
			      <div class="pluslet_body">
			<div id="loading" style="clear:both">
				<p><?php print _("Please wait while the links in your guide are being checked. This will vary depending on how many links are in your guide."); ?></p>
				<img src="<?php echo $AssetPath; ?>images/loading.gif" />
			</div>
			<div id="email_content">
			<?php
			ob_end_flush();
			$this->LinkCheckerFlush();
			$links_select = "SELECT title.title_id, title.title, title.description, location.location, location.access_restrictions " .
								 "FROM rank, title, location, location_title, source " .
								 "WHERE rank.title_id = title.title_id " .
								 "AND rank.source_id = source.source_id " .
								 "AND location_title.title_id = rank.title_id " .
								 "AND location.location_id = location_title.location_id " .
								 "AND location.format = 1 " .
								 "AND rank.subject_id = $lintSubjectID " .
								 "ORDER BY source.rs ASC, source.source ASC, rank.rank ASC, title.title ASC";
			$links_result = $db->query($links_select);
			?>
			<h3 style="clear:both;"><?php print _("Checking 'All Items By Source' Box:"); ?></h3>
			<?php if(!count($links_result)): ?>
				<p><?php print _("This guide does not have an 'All Items By Source' box."); ?></p>
			<?php else: ?>
				<table class="striper" style="width: 100%; margin: 20px 0 40px 0;">
					<thead>
						<tr>
							<th style="width: 60%"><?php print _("Link"); ?></th>
							<th><?php print _("Status"); ?></th>
							<th>&nbsp; <?php print _("HTTP Error Message"); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$link_list = array();
					foreach($links_result as $links_data)
					{
						if($links_data['access_restrictions'] == 2)
						{
							$links_data['location'] = $this->_proxy . $links_data['location'];
						}

						$url = "<a href=\"{$links_data['location']}\">{$links_data['title']}</a>";
						$this->_extra['title_id'] = $links_data['title_id'];

						$this->displayTableRowLinkStatus( $url, TRUE, FALSE );
						$this->LinkCheckerFlush();
						$this->displayTableRowLinkStatus( $links_data['description'], FALSE, FALSE );
					}
					?>
					</tbody>
				</table>
			<?php endif;
		$box_select = "SELECT p.pluslet_id, p.title, p.body, p.type
						FROM pluslet p INNER JOIN pluslet_section ps
						ON p.pluslet_id = ps.pluslet_id
						INNER JOIN section sec
						ON ps.section_id = sec.section_id
						INNER JOIN tab t
						ON sec.tab_id = t.tab_id
						INNER JOIN subject s
						ON t.subject_id = s.subject_id
						WHERE s.subject_id = $lintSubjectID
						AND p.type IN('Basic','Feed')
						ORDER BY ps.pcolumn ASC, ps.prow ASC";
			$box_result = $db->query($box_select);
			?>
			<?php if(!count($box_result)): ?>
				<p style="margin: 20px 0 40px 0;">This guide does not have any other pluslets.</p>
			<?php else:

					$link_list = array();
					foreach($box_result as $box_data)
					{
						?>
						<h3><?php print _("Checking"); ?> "<?php print $box_data['title']; ?>" <?php print _("Box:"); ?></h3>
						<?php

						if($box_data['type'] == 'Basic')
						{
							$bp = new Pluslet_Basic($box_data['pluslet_id'], '', $lintSubjectID);
							$body = $bp->output("", 'public');
						}
						elseif($box_data['type'] == 'Feed')
						{
							// Only checks the feed url itself.
							$body = "<a href=\"{$box_data['body']}\">{$box_data['title']}</a>";
						}
						?>
						<table class="striper" style="width: 100%; margin: 20px 0 40px 0;">
							<thead>
								<tr>
									<th style="width: 60%"><?php print _("Link"); ?></th>
									<th><?php print _("Status"); ?></th>
									<th>&nbsp; <?php print _("HTTP Error Message"); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							$this->displayTableRowLinkStatus( $body, FALSE );
							?>
							</tbody>
						</table>
						<?php
					}
			endif; ?>
			</div>
		</div>
		</div></div> <!-- close pure-u, close pure-g -->
		<script type="text/javascript">
		$(function (){

			$("input[name*=send_report2]").livequery('click', function(event) {

				var shortform = "<?php print $this->_extra['shortform']; ?>";
				var our_contents = $(this).attr("name");
				var our_linkresults = '<style type="text/css" media="all"></style>'
									+ '<div id="maincontent">'
									+ $("#email_content").html()
									+ '</div>';

				$("#loading").load("../guides/helpers/guide_bits.php",
				{type: 'email_link_report', sendto: our_contents, linkresults: our_linkresults, shortform: shortform}).fadeIn(1600);
				return false;
			});


		});

		</script>
	<?php
	}

	/**
	 * sp_LinkChecker::setShortForm() - setting shortform property
	 *
	 * @param int $lintSubjectID
	 * @return void
	 */
	private function setShortForm( $lintSubjectID )
	{
		$db = new Querier();
		$query = "SELECT shortform FROM subject WHERE subject_id = $lintSubjectID";
		$shortform = $db->query($query);
		$this->_extra['shortform'] = $shortform[0]['shortform'];

	}

	/**
	 * sp_LinkChecker::checkRecordsLinks() - check all records in databse links
	 *
	 * @return void
	 */
	public function checkRecordsLinks()
	{
		global $AssetPath;
		global $CpanelPath;

		$db = new Querier();

		?>
		<script>
		// Hide loading message/image when page is fully loaded.
		$(document).ready(function() {
			$('#loading').html('<?php echo $this->getMailOptions(TRUE);?>');
		});
		</script>
		<div class="box" style="clear:both;max-width:940px;">
			<div id="loading" style="clear:both">
				<p><?php print _("Please wait while the locations of all records are being checked. This will vary depending on how many locations there are."); ?></p>
				<img src="<?php echo $AssetPath; ?>images/loading.gif" />
			</div>
			<div id="email_content">
			<?php
			ob_end_flush();
			$this->LinkCheckerFlush();
			$links_select = "SELECT title.title_id, title.title, title.description, location.location, location.access_restrictions " .
								 "FROM  title " .
								 "INNER JOIN location_title " .
								 "ON title.title_id = location_title.title_id " .
								 "INNER JOIN location " .
								 "ON location_title.location_id = location.location_id";
			$links_result = $db->query($links_select);
			?>
			<h2 style="clear:both;"><?php print _("Checking All Records"); ?>:</h2>
			<?php if(!count($links_result)): ?>
				<p style="margin: 20px 0 40px 0;"><?php print _("No Record Locations Exist"); ?>.</p>
			<?php else: ?>
				<table class="striper" style="width: 100%; margin: 20px 0 40px 0;">
					<thead>
						<tr>
									<th style="width: 60%"><?php print _("Link"); ?></th>
									<th><?php print _("Status"); ?></th>
									<th>&nbsp; <?php print _("HTTP Error Message"); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$link_list = array();
					foreach($links_result as $links_data)
					{
						if($links_data['access_restrictions'] == 2)
						{
							$links_data['location'] = $this->_proxy . $links_data['location'];
						}

						$url = "<a href=\"{$links_data['location']}\">{$links_data['title']}</a>";
						$this->_extra['title_id'] = $links_data['title_id'];

						$this->displayTableRowLinkStatus( $url, TRUE, FALSE );
						$this->LinkCheckerFlush();
						$this->displayTableRowLinkStatus( $links_data['description'], FALSE, FALSE );
					}
					?>
					</tbody>
				</table>
			<?php endif; ?>
			</div>
		</div>
		<script type="text/javascript">
		$(function (){

			$("input[name*=send_report2]").livequery('click', function(event) {

				var our_contents = $(this).attr("name");
				var our_linkresults = '<style type="text/css" media="all"></style>'
									+ '<div id="maincontent">'
									+ $("#email_content").html()
									+ '</div>';

				$("#loading").load("../guides/helpers/guide_bits.php",
				{type: 'email_link_report', sendto: our_contents, linkresults: our_linkresults, shortform: "Records"}).fadeIn(1600);
				return false;
			});


		});

		</script>
	<?php
	}

	/**
	 * sp_LinkChecker::checkUrl() - check url to make sure link is available could open it
	 * and return array with error message error img soruce and error row style
	 *
	 * @param string $lstrURL
	 * @return array
	 */
	public function checkUrl( $lstrURL )
	{
		global $AssetPath;

		$cookie_file = dirname( dirname( dirname( dirname(__FILE__) ) ) ) . DIRECTORY_SEPARATOR .  "assets" . DIRECTORY_SEPARATOR . "cookies.txt";
		$error['message'] = '';
		$error['row_style'] = '';
		$rscCurl = curl_init($lstrURL);
		curl_setopt_array($rscCurl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HEADER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_COOKIESESSION => true,
			CURLOPT_COOKIEFILE => $cookie_file,
			CURLOPT_COOKIEJAR=> $cookie_file,
			CURLOPT_TIMEOUT => $this->_timeout
		));
		$lstrResponse = curl_exec($rscCurl);
		$http_status = curl_getinfo($rscCurl, CURLINFO_HTTP_CODE);

		if( $http_status != "200" )
		{
			list($header, $body) = !$lstrResponse ? array( NULL, NULL ) : explode("\r\n\r\n", $lstrResponse, 2);

			if(isset($header))
			{
				$headers = explode("\n", $header);
				foreach($headers as $line)
				{
					if(strncasecmp("HTTP", $line, 4) == 0)
					{
						$error['message'] = $line;
						break;
					}
				}
			}else
			{
				$error['message'] = 'No response from server.';
			}
			$error['img_src'] = "<i class=\"fa fa-exclamation-triangle fa-lg\" title=\"" . _("Possible error") . "\"></i>";
			$error['row_style'] = ' style="background: #ffff33;"';
		}
		else
		{
			$error['img_src'] = "<i class=\"fa fa-check fa-lg\" title=\"" . _("Looks okay") . "\"></i>";
		}

		curl_close($rscCurl);

		if(file_exists($cookie_file))
		{
			unlink($cookie_file);
		}

		return $error;
	}

	/**
	 * sp_LinkChecker::displayTableRowLinkStatus() - extracts links from the body parameter
	 * and display HTML table rows with the statuses of the links
	 *
	 * @param string $body
	 * @param boolean $isrecord
	 * @param boolean $displaynolinks
	 * @return void
	 */
	private function displayTableRowLinkStatus( $body = "", $isrecord = FALSE, $displaynolinks = TRUE )
	{
		global $CpanelPath;
		$lboolEchoed = FALSE;

		if( $body == "" ) return;

		preg_match_all('/<a.*href="(.*)".*>(.*)<\/a>/Us', $body, $matches, PREG_SET_ORDER);
		if(count($matches) != 0)
		{
			foreach($matches as $link)
			{
				// Don't want to extract mailto, javascript: , etc. links, just http
				if(preg_match('/^http/', $link[1]))
				{
					$lboolEchoed = TRUE;
					$error = $this->checkUrl($link[1]);
					$link[1] = preg_replace("/^.*\/login\?url=http/", 'http', $link[1]);
					echo "<tr$error[row_style]>\n";
					echo "<td style=\"padding-left:18px;\">";

					if( $isrecord )
					{
						echo "<a href=\"{$CpanelPath}records/record.php?record_id={$this->_extra['title_id']}\" title=\"Edit this record in new window\" target=\"_blank\">";
						echo "<i class=\"fa fa-pencil fa-lg\" title=\"" . _("Edit") . "\"></i></a>&nbsp;";
					}

					echo "<a href=\"$link[1]\" target=\"_blank\">$link[2]</a></td>\n" .
							"<td>$error[img_src]</td>\n" .
							"<td>$error[message]</td>\n";
					echo "</tr>\n";
				}
				$this->LinkCheckerFlush();
			}
			if(!$lboolEchoed && $displaynolinks) print "<tr><td>" . _("This box does not contain any links") . "</td><td>" . _("N/A") . "</td></tr>\n"; //print out something if nothing was echoed
		}
		elseif( $displaynolinks )
		{
			print "<tr><td>" . _("This box does not contain any links") . "</td><td>" . _("N/A") . "</td></tr>\n";
		}
	}

	/**
	 * sp_LinkChecker::LinkCheckerFlush() - flush output to show process step by step
	 * and to prevent timeout
	 *
	 * @return void
	 */
	private function LinkCheckerFlush()
	{
		ob_flush();
		flush();
		usleep(50000);
	}

	/**
	 * sp_LinkChecker::getMailOptions() - return mail options based on passed parameter
	 *
	 * @param boolean $onlyCurrent
	 * @return string
	 */
	private function getMailOptions( $onlyCurrent = FALSE )
	{
		if( !$onlyCurrent)
		{
			$mailReport = "<p>" ._("Would you like to mail this report to someone?") .
				"</p><br /><input type=\"submit\" class=\"button pure-button pure-button-primary\" name=\"send_report2owner\" value=\"" . _("Send report to: ")
	        . $_SESSION["email"] . "\"> &nbsp; <input type=\"submit\" class=\"button pure-button pure-button-primary\" name=\"send_report2all\" value=\""
	        . _("Send report to All Guide Owners") . "\"><br /><br />";
		}else
		{
			$mailReport = "<p>" ._("Would you like to mail this report to someone?") .
				"</p><br /><input type=\"submit\" class=\"button pure-button pure-button-primary\"  name=\"send_report2owner\" value=\"" . _("Send report to: ")
	        . $_SESSION["email"] . "\"><br /><br />";
		}

		return $mailReport;
	}
}
?>