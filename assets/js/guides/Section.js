/**
 * 
 * 
 * 
 * @constructor Section
 * 
 * 
 */

function Section() {

	var mySection = {

		settings : {
			sectionDataPath : "helpers/section_data.php"
		},
		strings : {},
		bindUiActions : function() {
		},
		init : function() {
		},
		makeAddSection : function(lstrSelector)
		///////////////
		//function to add section to current tab	
		//////////////
		{

			$(lstrSelector).on('click', function() {

				var lintSelected = $(tabs).tabs('option', 'selected');

				$.ajax({
					url : mySection.settings.sectionDataPath,
					type : "POST",
					data : {
						action : 'create'
					},
					dataType : "html",
					success : function(html) {

						$('div#tabs-' + lintSelected).append(html);
						$(document).scrollTop($('body').height());

						// Make sure that the new section can accept drops
						var drop = new Drag();
						drop.makeDropable(".dropspotty");
					}
				});
			});
		}
	};

	return mySection;
}
