<style>
.tab-click-header {
display:none;
}

.total-views-count {

font-size: 2em;

}

.total-views-header {


}
</style>


<div id="analytics_options_content" class="second-level-content"
	style="display: none;">
	<h3><?php print _("Analytics"); ?></h3>

	<div class="analytics_display">

		<h1 class="total-views-header">Total Views:</h1>
		<span class="total-views-count"></span> </br> 
			<span class="tab-click-header">Clicks on Tabs:</span>
			
		<ul class="tab-clicks">

		</ul>
	</div>
	<script>
	  document.addEventListener("DOMContentLoaded", function(event) {
	var a = Analytics();
	a.init();
	  });
	  
	</script>