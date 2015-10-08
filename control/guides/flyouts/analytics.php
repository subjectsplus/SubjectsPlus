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
$(document).ready(function() {
	    $('.tab-clicks').empty();
	    var short_form = $('#shortform').data();
	    console.log(short_form);
		$.get("./helpers/stats_data.php?short_form=" + short_form.shortform, function(data) {

			console.log(data);
			console.log(data.total_views);
		$(".total-views-count").html(data.total_views);


		if (data.tab_clicks != "") {
			$('.tab-click-header').show();
						for (key in data.tab_clicks) {

			console.log(key);
			
			$(".tab-clicks").append("<li class='tab-click'>" + key + " : " + data.tab_clicks[key] + "</li>");
			
		}

		}

		});
});
	</script>