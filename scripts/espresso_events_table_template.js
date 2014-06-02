jQuery(document).ready(function($){

	$("#ee_filter_cat").change(function() {
		var ee_filter_cat_id = $("option:selected").attr('class');
		console.log(ee_filter_cat_id);
		$("#ee_filter_table .espresso-table-row").show();
		$("#ee_filter_table .espresso-table-row").each(function() {
			if(!$(this).hasClass(ee_filter_cat_id)) {
				$(this).hide();
			}
		});
		if( ee_filter_cat_id == 'ee_filter_show_all') {
			$("#ee_filter_table .espresso-table-row").show();
		}
	});


});