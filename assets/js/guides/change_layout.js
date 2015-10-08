function changeLayout(first_column, second_column) {

	var current_tab = $('#tabs').tabs('option', 'selected');
	var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');

	$("#slider_" + slider_section_id).slider('values',0, first_column);
	$("#slider_" + slider_section_id).slider('values',1, second_column );
}