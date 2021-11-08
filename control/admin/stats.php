<?php
/**
 *   @file stat.php
 *   @brief 
 *
 *   @author Jamie Little (little9)
 *   @date Aug 2016
 *   
 */

$subcat = "analytics";
$page_title = "SubjectsPlus Usage Statistics";

include_once(__DIR__ . "/../includes/header.php");

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats;

$db = new Querier;
$stats = new Stats($db);
$total_views = $stats->getTotalViews();
$total_per_guide = $stats->getTotalViewsPerGuide();
$total_tab_clicks_per_guide = $stats->getTopTabsPerGuide();
$total_external_link_clicks = $stats->getTopExternalLinks();

?>

<!-- Total Views Last Month -->
<div class="pure-g">
    <div class="pure-u-1-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Views Last Month</div>
	    </div>
	    <div class="pluslet_body">
		<?php print_r($total_views[0]['total_views_last_month']); ?>
	    </div>
	</div>
    </div>
</div>

<!-- Total External Link Clicks Per Guide -->
<div class="pure-g">
    <div class="pure-u-1-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Views</div>
	    </div>
	    <div class="pluslet_body">
		<table>
		    <thead>
			<tr>
			    <td>Guide Name</td>
			    <td>Number of Views</td>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_per_guide as $guide_total) { ?>
			    <tr>
				<td>
				    <?php echo $guide_total['page_title']; ?>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>


<!-- Total Tab Clicks Per Guide -->
<div class="pure-g">
    <div class="pure-u-1-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Tab Clicks Per Guide</div>
	    </div>
	    <div class="pluslet_body">
		<table>
		    <thead>
			<tr>
			    <td>Tab Name</td>
			    <td>Guide Shortform</td>
			    <td>Number of Clicks</td>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_tab_clicks_per_guide as $guide_total) { ?>
			    <tr>
				<td>
				    <?php echo $guide_total['tab_name']; ?>
				</td>
				<td>
				    <?php echo $guide_total['subject_short_form']; ?>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>


<!-- Total External Link Clicks -->
<div class="pure-g">
    <div class="pure-u-1-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Link Clicks</div>
	    </div>
	    <div class="pluslet_body">
		<table>
		    <thead>
			<tr>
			    <td>Link URL</td>
			    <td>Number of Clicks</td>
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_external_link_clicks as $guide_total) { ?>
			    <tr>
				<td>
				    <?php echo $guide_total['link_url']; ?>
				</td>
				<td>
				    <?php echo $guide_total['num']; ?>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>




<?php
include_once(__DIR__ . "/../includes/footer.php");