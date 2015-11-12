<div id="analytics_options_content" class="second-level-content" style="display: none;">
	
	<h3><?php print _("Analytics"); ?></h3>

	<div class="analytics_display">

		<div class="master-counter">
			<h2 class="total-views-header">Total Views: <span class="total-views-count"></span></h2>
		</div>	
		
		<h4 class="tab-click-header">Clicks on Tabs:</h4>

		<ul class="tab-clicks panel-list"></ul>

	</div><!--end analytics_display-->

<script>
	  document.addEventListener("DOMContentLoaded", function(event) {
	var a = analytics();
	a.init();
	  });
	  
</script>
