function changeLayout(first_column, second_column) {

	$("[id^=slider]").first().slider('values',0, first_column);
	$("[id^=slider]").first().slider('values',1,second_column );


}