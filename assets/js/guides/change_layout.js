function moveColumnContent(source_column, target_column) {

    var current_tab = $('#tabs').tabs('option', 'selected');
    var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');
    var content = $("#" + slider_section_id + " #container-" + source_column + " .portal-column").children();

    $("#" + slider_section_id + "  #container-" + target_column + " .portal-column").append(content);
 }


function changeLayout(first_column, second_column) {

	var current_tab = $('#tabs').tabs('option', 'selected');
	var slider_section_id = $('#tabs-' + parseInt(current_tab)).children().attr('id');

	$("#slider_" + slider_section_id).slider('values',0, first_column);
	$("#slider_" + slider_section_id).slider('values',1, second_column );
}


//Change layout click events
$( "#col-single" ).click(function() {

  changeLayout(0, 14);
  checkDataLayout();
  moveColumnContent(0, 1);
  moveColumnContent(2, 1);
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

$( "#col-double" ).click(function() {

  changeLayout(6, 12);
  checkDataLayout();
  moveColumnContent(2, 1);
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

$( "#col-48" ).click(function() {
  changeLayout(4, 24);
  checkDataLayout();
  moveColumnContent(2, 1);
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

$( "#col-84" ).click(function() {
  changeLayout(8, 12);
  checkDataLayout();
  moveColumnContent(2, 1);
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

$( "#col-triple" ).click(function() {
  changeLayout(4, 8);
  checkDataLayout();
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

$( "#col-363" ).click(function() {
  changeLayout(3, 9);
  checkDataLayout();
  selectedLayout();
  $(this).addClass("active-layout-icon");
});

