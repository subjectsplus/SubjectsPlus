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

include("../includes/header.php");

global $stats_enabled;
if (!$stats_enabled){
    print "
    <div class=\"master-feedback\" style=\"display:block;\">The Stats are disabled. Sorry for the inconvenience</div>
    ";
    die();
}

use SubjectsPlus\Control\Querier;
use SubjectsPlus\Control\Stats;

$db = new Querier;
$stats = new Stats($db);
$total_views_last_month = $stats->getTotalViewsFromLastMonth();
$total_views = $stats->getAllTotalViews();
$total_per_guide = $stats->getTotalViewsPerGuide();
$total_tab_clicks_per_guide = $stats->getTopTabsPerGuide();
$total_external_link_clicks = $stats->getTopExternalLinks();

?>

<style>
 .total-views {
     font-size:5em;
 }
</style>

<div class="pure-g">
    <!-- Total Views Last Month -->
        <div class="pure-u-1-3">
            <div class="pluslet no_overlflow">
                <div class="titlebar">
                    <div class="titlebar_text">Total Views From Last 30 Days</div>
                </div>
                <div class="pluslet_body total-views">
                    <?php print_r($total_views_last_month); ?>
                </div>
            </div>
        </div>

    <!-- Total Views Month -->
        <div class="pure-u-1-3">
            <div class="pluslet no_overlflow">
                <div class="titlebar">
                    <div class="titlebar_text">
                        <?php
                        if ($stats->emptyStats())
                            echo "Total Views";
                        else
                            echo "Total Views since " . $stats->getFirstRecordMonthAndYear()
                        ?>
                    </div>
                </div>
                <div class="pluslet_body total-views">
                    <?php print_r($total_views); ?>
                </div>
            </div>
        </div>
</div>



<!-- Total External Link Clicks Per Guide -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Views Per Guide Last Month</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
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



<!-- Total Tab Clicks Per Guide -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Tab Clicks Per Guide Last Month</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
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


<!-- Total External Link Clicks -->

    <div class="pure-u-3-3">
	<div class="pluslet no_overlflow">
	    <div class="titlebar">
		<div class="titlebar_text">Total Link Clicks Per Guide Last Month</div>
	    </div>
	    <div class="pluslet_body">
		<table class="stats-table">
		    <thead>
			<tr>
			    <td>Guide Name</td>
			    <td>Link URL</td>
			    <td>Number of Clicks</td>
			    
			</tr>
		    </thead>
		    <tbody>
			<?php foreach ($total_external_link_clicks as $guide_total) { ?>
			    <tr>
				<td>
				   <?php echo $guide_total['guide_name']; ?>
				</td>
				<td>
				    <a href="<?php echo $guide_total['link_url']; ?>"><?php echo $guide_total['link_url']; ?></a>
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


<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"></link>
<script>
 $(document).ready(function(){
     $('.stats-table').DataTable({
         /* No ordering applied by DataTables during initialisation */
         "order": []
     });
 });
</script>


<?php 
include_once("../control/includes/footer.php");
?>
