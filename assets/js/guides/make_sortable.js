////////////////////////////
// MAKE COLUMNS SORTABLE
// Make "Save Changes" button appear on sorting
////////////////////////////

function makeSortable(lstrSelector, lstrType) {

	var sortable_element = $(lstrSelector);

	if (lstrType === 'sections') {
		sortable_element.sortable({
			opacity : 0.7,
			cancel : '.unsortable',
			handle : 'img.section_sort',
			update : function(event, ui) {
				$("#response").hide();
				$("#save_guide").fadeIn();

			},
			start : function(event, ui) {
				$(ui.item).find('.dropspotty').hide();
				$(ui.item).find('.pluslet').hide();
				$(ui.item).height('2em');
				$(ui.item).width('auto');
			},
			stop : function(event, ui) {
				$(ui.item).find('.dropspotty').show();
				$(ui.item).find('.pluslet').show();
			}
		});
	} else {
		sortable_element.sortable({

			connectWith : [ '.portal-column-0', '.portal-column-1',
					'.portal-column-2' ],
			opacity : 0.7,
			tolerance : 'intersect',
			cancel : '.unsortable',
			handle : 'img.pluslet_sort',
			update : function(event, ui) {
				$("#response").hide();
				$("#save_guide").fadeIn();

			},
			start : function(event, ui) {
				$(ui.item).children('.pluslet_body').hide();
				$(ui.item).children().children('.titlebar_text').hide();
				$(ui.item).children().children('.titlebar_options').hide();
				$(ui.item).height('2em');
				$(ui.item).width('auto');
			},
			stop : function(event, ui) {
				$(ui.item).children('.pluslet_body').show();
				$(ui.item).children().children('.titlebar_text').show();
				$(ui.item).children().children('.titlebar_options').show();

			}
		});
	}
}
