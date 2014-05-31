jQuery(document).ready(function(){

	jQuery("#ee_filter_cat").change(function() {
		var ee_filter_cat_id = jQuery("option:selected").attr('class');
		console.log(ee_filter_cat_id);
		jQuery("#ee_filter_table .espresso-table-row").show();
		jQuery("#ee_filter_table .espresso-table-row").each(function() {
			if(!jQuery(this).hasClass(ee_filter_cat_id)) {
				jQuery(this).hide();
			}
		});
		if( ee_filter_cat_id == 'ee_filter_show_all') {
			jQuery("#ee_filter_table .espresso-table-row").show();
		}
	});


});