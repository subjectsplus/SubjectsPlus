$('.guides').select2({
	"width" : "75%"
});

$('.import_guide').prop("disabled", true);

$('.dropspotty').each(function() {

	try {

		var data_children = $(this).attr('data-children');
		var childs = data_children.split();

		childs.forEach(function(data) {

			var split_ids = data.split(',');
			console.log(split_ids);
			split_ids.forEach(function(data) {

				$('#' + data).hide();
				console.log(data);
			})

		});

	} catch (e) {

	}

});

$('.guides').on("change", function() {

	$('.import_guide').prop("disabled", true);

});

function guidesHandler(guides) {

	console.log(guides[0]);

	for (var i = 0; i < guides.length; i++) {

		var guide = guides[i];

		console.log(guide[0]);

		var table_data = "<tr>" + "<td>" + guide[0].box[0].box_name + "</td>"
				+ "<td>" + guide[0].box[1].box_type + "</td>";

		$('.guide_results').append(table_data);
		$('.guide_results').show();
		$('.loading').remove();
	}
}

function linksHandler(titles) {
	var table_data;

	for (var i = 0; i < titles.length; i++) {

		var title = titles[i];

		table_data += "<tr>" + "<td>" + title[0].title + "</td>" + "<td>"
				+ title[1].status + "</td>" + "<td>" + title[2].url + "</td>";
		// "<td>" + title[3].working_link + "</td>";

	}
	$('.link-results-body').empty().append(table_data);
	$('.link_results').show();
	$('.import-output').append(
			"<h1 class=\"links-success\">" + "Links Imported Successfully"
					+ "</h1>");

	$('.loading').remove();
	$('.import_guide').prop("disabled", false);
	$('.view-links-results').prop("disabled", false);
}

function importGuides(selected_guide_id, selected_guide_name, url) {

	var guide = [ selected_guide_id, selected_guide_name ];

	console.log(selected_guide_id);
	console.log(selected_guide_name);
	
	var staff_id = $('.staff-id').data().staff_id;
	console.log("Current staff id:" + staff_id);
	
	$.ajax({
				type : "GET",
				url : url,
				data : { "libguide" : selected_guide_id,
						 "staff_id" : staff_id },
				error : function(data) {

					$('.import-output')
							.append(
									"<p class='import-feedback'>There was an error importing this guide. You may be trying to import a guide that has already been imported.</p>");
					$('.import-output').append(
							"<p class='import-error'>" + data.responseText
									+ "</p>");
					console.log(data);

				},
				success : function(data) {
					console.log(data);

					if (!data) {
						$('.import-output')
								.append(
										"<p class='import-feedback'>There was problem importing this guide</p>");
						$('.loading').remove();

					}

					if (data.titles) {

						linksHandler(data.titles);
					}

					if (data.titles && data.titles.length
							&& data.titles.length === 0) {

						$('.loading')
								.html(
										"The importer couldn't find any links in this guide.");

					}

					if (data.imported_guide) {
						console.log(data);
						guidesHandler(data);

						$('.import-output')
								.append(
										"<h1 class='import-feedback'>Sucessfully Imported <a target=\"_blank\" href='../guide.php?subject_id="
												+ data.imported_guide[0]
												+ "'>"
												+ selected_guide_name
												+ "</a></h1>");
						$('.import-output')
								.append(
										"<p class='import-feedback'>You can compare your guide with its <a target=\"_blank\" href='http://libguides.miami.edu/content.php?pid="
												+ data.imported_guide[0]
												+ "'>original LibGuide</a>.</p>");

						$('.import-output')
								.append(
										"<p class='import-feedback'>Click here to view all your <a target=\"_blank\" href='../../'> SubjectsPlus guides</a></p>");
						$('.previously-imported').append(
								"<li><a target=\"_blank\" href='../guide.php?subject_id="
										+ data.imported_guide[0] + "'>"
										+ selected_guide_name + "</a></li>");

						$('.loading').remove();
					}
				}

			});
}

$('.import_links').on(
		'click',
		function() {

			var selected_guide_name = $(this).parent().parent().find(
					'option:selected').text();
			var selected_guide_id = $(this).parent().parent().find(
					'option:selected').val();

			$('.import-output').append(
					"<div class=\"loading loader\">Loading... </div>");

			importGuides(selected_guide_id, selected_guide_name,
					"import_libguides_links.php");

		});

$('.import_guide').on(
		'click',
		function() {

			var selected_guide_name = $(this).parent().parent().find(
					'option:selected').text();
			var selected_guide_id = $(this).parent().parent().find(
					'option:selected').val();

			importGuides(selected_guide_id, selected_guide_name,
					"import_libguides.php");
			$('.import-output').append(
					"<div class=\"loading loader\">Loading...</div>");

		});

$('.view-links-results').on('click', function() {

	$('.link_results').toggle();

})
